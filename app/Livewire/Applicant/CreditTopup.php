<?php

namespace App\Livewire\Applicant;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class CreditTopup extends Component
{
    public string $selectedPackage = 'popular';
    public bool $isProcessing = false;

    // Prototype Instant Modal state
    public bool $showModal = false;
    public ?Order $modalOrder = null;
    public bool $isPaidSuccess = false;

    public function mount(?string $order = null)
    {
        $orderNumber = $order ?: request('order_id') ?: request('order');
        if ($orderNumber) {
            $midtrans = app(MidtransService::class);
            $found = Order::where('order_number', $orderNumber)
                ->where('user_id', Auth::id())
                ->first();

            if ($found && !$found->isPaid()) {
                $statusParam = request('transaction_status');
                $isPaidFromParam = in_array($statusParam, ['settlement', 'capture']);

                // Cek status via API Midtrans
                $statusData = $midtrans->checkTransactionStatus($found->order_number);
                $transStatus = $statusData['transaction_status'] ?? null;
                $fraudStatus = $statusData['fraud_status'] ?? null;
                $isPaidFromApi = ($transStatus === 'settlement' || ($transStatus === 'capture' && $fraudStatus === 'accept'));

                if ($isPaidFromParam || $isPaidFromApi) {
                    Order::grantOrderBenefits($found, $statusData ?: request()->all());
                    $freshCredits = Auth::user()->applicantProfile?->fresh()->application_credits ?? 0;
                    $this->dispatch('credits-updated', credits: $freshCredits);
                    session()->flash('notify', 'Pembayaran berhasil dikonfirmasi! Kuota Anda telah aktif.');
                }
            } elseif ($found && $found->isPaid()) {
                session()->flash('notify', 'Pembayaran berhasil dikonfirmasi! Kuota Anda telah aktif.');
            }
        }
    }

    public function selectPackage(string $key): void
    {
        $this->selectedPackage = $key;
    }

    public function buyPackage(string $packageKey, MidtransService $midtrans): void
    {
        $this->isProcessing = true;
        $packages = Order::packages();

        if (!isset($packages[$packageKey])) {
            $this->dispatch('notify', ['message' => 'Paket tidak valid.', 'type' => 'error']);
            $this->isProcessing = false;
            return;
        }

        $pkg = $packages[$packageKey];
        $user = Auth::user();

        // Buat order baru
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $user->id,
            'item_type' => $pkg['type'],
            'package_key' => $pkg['key'],
            'item_name' => $pkg['name'],
            'credits_amount' => $pkg['credits'],
            'original_amount' => $pkg['original_price'],
            'gross_amount' => $pkg['price'],
            'status' => Order::STATUS_PENDING,
        ]);

        try {
            $midtrans->createSnapToken($order, $user);
        } catch (\Throwable $e) {
            // Tetap jalan di mode prototipe
        }

        $this->modalOrder = $order;
        $this->showModal = true;
        $this->isPaidSuccess = false;
        $this->isProcessing = false;
    }

    public function confirmQuickPayment(): void
    {
        if ($this->modalOrder) {
            Order::grantOrderBenefits($this->modalOrder, ['payment_type' => 'qris_instant']);
            $this->isPaidSuccess = true;
            $this->modalOrder->refresh();
            $freshCredits = Auth::user()->applicantProfile?->fresh()->application_credits ?? 0;
            $this->dispatch('credits-updated', credits: $freshCredits);
            $this->dispatch('notify', [
                'message' => "Pembayaran berhasil dikonfirmasi! Kuota Anda telah ditambahkan.",
                'type' => 'success'
            ]);
        }
    }

    public function openOrderModal(int $orderId): void
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->first();
        if ($order) {
            $this->modalOrder = $order;
            $this->showModal = true;
            $this->isPaidSuccess = $order->isPaid();
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalOrder = null;
        $this->isPaidSuccess = false;
    }

    public function openRealSnap(): void
    {
        if ($this->modalOrder && $this->modalOrder->snap_token) {
            $this->dispatch('pay-with-snap', [
                'snapToken' => $this->modalOrder->snap_token,
                'orderNumber' => $this->modalOrder->order_number,
                'orderId' => $this->modalOrder->id,
                'amount' => $this->modalOrder->gross_amount,
                'itemName' => $this->modalOrder->item_name,
            ]);
        }
    }

    public function syncOrder(int $orderId, MidtransService $midtrans): void
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->first();
        if (!$order) {
            return;
        }

        if ($order->isPaid()) {
            $this->dispatch('notify', ['message' => 'Pesanan ini sudah lunas dan kuota telah aktif.', 'type' => 'info']);
            return;
        }

        $statusData = $midtrans->checkTransactionStatus($order->order_number);
        $transStatus = $statusData['transaction_status'] ?? null;
        $fraudStatus = $statusData['fraud_status'] ?? null;
        $isPaid = ($transStatus === 'settlement' || ($transStatus === 'capture' && $fraudStatus === 'accept'));

        if ($isPaid) {
            Order::grantOrderBenefits($order, $statusData ?: []);
            $freshCredits = Auth::user()->applicantProfile?->fresh()->application_credits ?? 0;
            $this->dispatch('credits-updated', credits: $freshCredits);
            $this->dispatch('notify', ['message' => 'Pembayaran berhasil dikonfirmasi! Kuota bertambah.', 'type' => 'success']);
        } else {
            // Di mode demo/prototype, beri kemudahan instant approval
            Order::grantOrderBenefits($order);
            $freshCredits = Auth::user()->applicantProfile?->fresh()->application_credits ?? 0;
            $this->dispatch('credits-updated', credits: $freshCredits);
            $this->dispatch('notify', ['message' => 'Status pembayaran berhasil disinkronkan. Kuota aktif.', 'type' => 'success']);
        }
    }

    public function render()
    {
        $user = Auth::user();
        $profile = $user->applicantProfile?->fresh();
        $packages = Order::packages();
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.applicant.credit-topup', [
            'profile' => $profile,
            'packages' => $packages,
            'recentOrders' => $recentOrders,
            'clientKey' => config('services.midtrans.client_key') ?: env('MIDTRANS_CLIENT_KEY', ''),
        ])->title('Isi Ulang Kuota & Bundling — NEAR JOB');
    }
}
