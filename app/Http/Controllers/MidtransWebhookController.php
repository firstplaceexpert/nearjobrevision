<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request, MidtransService $midtrans): JsonResponse
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook Payload Received:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'unknown';

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return response()->json(['message' => 'Parameter tidak lengkap'], 400);
        }

        // 1. Verifikasi Keamanan Signature SHA-512
        if (!$midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning("Percobaan akses webhook tidak sah dengan signature palsu pada order {$orderId}");
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        // 2. Cari Order
        $order = Order::where('order_number', $orderId)->first();
        if (!$order) {
            Log::error("Order {$orderId} tidak ditemukan di database");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 3. Proses Transaksi Atomik dengan Lock
        return DB::transaction(function () use ($order, $transactionStatus, $fraudStatus, $paymentType, $payload) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();

            $isSuccess = false;
            if ($transactionStatus === 'capture') {
                $isSuccess = ($fraudStatus === 'accept');
            } elseif ($transactionStatus === 'settlement') {
                $isSuccess = true;
            }

            if ($isSuccess) {
                // Idempotency: Jika sudah settlement sebelumnya, abaikan penambahan kuota ganda
                if ($lockedOrder->isPaid()) {
                    return response()->json(['status' => 'already_processed', 'message' => 'Order already paid']);
                }

                $lockedOrder->update([
                    'status' => Order::STATUS_SETTLEMENT,
                    'payment_type' => $paymentType,
                    'payment_details' => $payload,
                    'paid_at' => now(),
                ]);

                // Eksekusi Benefit ke Akun Pengguna
                $user = $lockedOrder->user;
                $profile = $user->applicantProfile;

                if ($profile) {
                    // Tambahkan Kuota Kredit Lamaran
                    if ($lockedOrder->credits_amount > 0) {
                        $profile->increment('application_credits', $lockedOrder->credits_amount);
                        Log::info("Menambahkan {$lockedOrder->credits_amount} kredit ke user {$user->id} ({$user->name})");
                    }

                    // Buka Kunci Akses CV ATS jika membeli paket CV atau Bundle
                    if ($lockedOrder->item_type === 'cv_generator' || $lockedOrder->item_type === 'bundle') {
                        $profile->update([
                            'cv_generated' => true,
                            'cv_data' => [
                                'generated_at' => now()->toIso8601String(),
                                'order_id' => $lockedOrder->id,
                                'package' => $lockedOrder->package_key,
                            ],
                        ]);
                        Log::info("Aktivasi CV ATS Profesional untuk user {$user->id} ({$user->name})");
                    }
                }

                return response()->json(['status' => 'success', 'message' => 'Payment settled and benefits granted']);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $lockedOrder->update([
                    'status' => $transactionStatus,
                    'payment_details' => $payload,
                ]);
                return response()->json(['status' => 'cancelled', 'message' => "Order marked as {$transactionStatus}"]);
            }

            return response()->json(['status' => 'pending', 'message' => 'Order awaiting payment']);
        });
    }

    /**
     * Endpoint untuk mengecek status order (Client Polling / Fallback)
     */
    public function checkStatus(Order $order, MidtransService $midtrans): JsonResponse
    {
        if ($order->isPaid()) {
            return response()->json([
                'status' => 'settlement',
                'is_paid' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi!',
                'credits' => auth()->user()?->applicantProfile?->application_credits ?? 0,
            ]);
        }

        // Cek langsung ke server Midtrans
        $midtransData = $midtrans->checkTransactionStatus($order->order_number);
        if ($midtransData) {
            $transStatus = $midtransData['transaction_status'] ?? null;
            $fraudStatus = $midtransData['fraud_status'] ?? null;

            $isSuccess = ($transStatus === 'settlement' || ($transStatus === 'capture' && $fraudStatus === 'accept'));
            if ($isSuccess && !$order->isPaid()) {
                DB::transaction(function () use ($order, $midtransData) {
                    $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
                    if (!$lockedOrder->isPaid()) {
                        $lockedOrder->update([
                            'status' => Order::STATUS_SETTLEMENT,
                            'payment_type' => $midtransData['payment_type'] ?? 'unknown',
                            'payment_details' => $midtransData,
                            'paid_at' => now(),
                        ]);

                        $profile = $lockedOrder->user->applicantProfile;
                        if ($profile) {
                            if ($lockedOrder->credits_amount > 0) {
                                $profile->increment('application_credits', $lockedOrder->credits_amount);
                            }
                            if ($lockedOrder->item_type === 'cv_generator' || $lockedOrder->item_type === 'bundle') {
                                $profile->update([
                                    'cv_generated' => true,
                                    'cv_data' => [
                                        'generated_at' => now()->toIso8601String(),
                                        'order_id' => $lockedOrder->id,
                                    ],
                                ]);
                            }
                        }
                    }
                });

                return response()->json([
                    'status' => 'settlement',
                    'is_paid' => true,
                    'message' => 'Pembayaran berhasil!',
                    'credits' => auth()->user()?->applicantProfile?->fresh()->application_credits ?? 0,
                ]);
            }
        }

        return response()->json([
            'status' => $order->status,
            'is_paid' => false,
            'message' => 'Menunggu konfirmasi pembayaran.',
        ]);
    }
}
