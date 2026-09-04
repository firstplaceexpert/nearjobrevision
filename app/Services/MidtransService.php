<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key') ?: env('MIDTRANS_SERVER_KEY', '');
        $this->clientKey = config('services.midtrans.client_key') ?: env('MIDTRANS_CLIENT_KEY', '');
        $this->isProduction = (bool) (config('services.midtrans.is_production') ?: env('MIDTRANS_IS_PRODUCTION', false));
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    public function getSnapUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function getApiBaseUrl(): string
    {
        return $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';
    }

    /**
     * Request Snap Token ke API Midtrans
     */
    public function createSnapToken(Order $order, User $user): string
    {
        // Jika server key belum disetel (mode pengembangan lokal/demo), hasilkan mock token yang aman
        if (empty($this->serverKey)) {
            Log::warning('Midtrans Server Key belum dikonfigurasi. Menggunakan mock Snap Token untuk pengujian.');
            $mockToken = 'mock_snap_' . bin2hex(random_bytes(16));
            $order->update(['snap_token' => $mockToken]);
            return $mockToken;
        }

        $phone = $user->whatsapp ?: ($user->applicantProfile?->whatsapp ?: '08123456789');

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->gross_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $phone,
            ],
            'item_details' => [
                [
                    'id' => $order->package_key ?: $order->item_type,
                    'price' => (int) $order->gross_amount,
                    'quantity' => 1,
                    'name' => mb_substr($order->item_name, 0, 50),
                ],
            ],
            'callbacks' => [
                'finish' => route('applicant.topup', ['order' => $order->order_number]),
            ],
        ];

        try {
            $req = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(15);

            // Pada Windows localhost yang belum mengonfigurasi curl.cainfo
            if (app()->isLocal() || env('APP_ENV') === 'local') {
                $req = $req->withoutVerifying();
            }

            $response = $req->post($this->getSnapUrl(), $payload);

            if ($response->successful()) {
                $token = $response->json('token');
                $order->update(['snap_token' => $token]);
                return $token;
            }

            Log::error('Gagal mengambil Snap Token Midtrans: ' . $response->body());
            throw new \Exception('Midtrans error: ' . ($response->json('error_messages')[0] ?? 'Gagal membuat transaksi'));
        } catch (\Throwable $e) {
            Log::error('Exception saat request Snap Token: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verifikasi Tanda Tangan Kriptografis (SHA-512) Webhook Midtrans
     * Formula: sha512(order_id + status_code + gross_amount + ServerKey)
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        if (empty($this->serverKey)) {
            return true; // Mode fallback dev
        }

        $input = $orderId . $statusCode . $grossAmount . $this->serverKey;
        $expected = hash('sha512', $input);

        return hash_equals($expected, $signatureKey);
    }

    /**
     * Cek status transaksi langsung ke Midtrans API (Fallback jika webhook delay)
     */
    public function checkTransactionStatus(string $orderNumber): ?array
    {
        if (empty($this->serverKey)) {
            return null;
        }

        try {
            $req = Http::withBasicAuth($this->serverKey, '')
                ->timeout(10);

            if (app()->isLocal() || env('APP_ENV') === 'local') {
                $req = $req->withoutVerifying();
            }

            $response = $req->get($this->getApiBaseUrl() . "/{$orderNumber}/status");

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            Log::error('Gagal memeriksa status transaksi Midtrans: ' . $e->getMessage());
        }

        return null;
    }
}
