<?php

namespace App\Livewire\Applicant;

use App\Models\ApplicantProfile;
use App\Models\Order;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class CvGenerator extends Component
{
    public User $user;
    public ?ApplicantProfile $profile = null;
    public bool $isPaid = false;

    // Prototype Instant Modal
    public bool $showModal = false;
    public ?Order $modalOrder = null;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->profile = $this->user->applicantProfile;
        $this->isPaid = (bool) ($this->profile?->cv_generated);
    }

    public function buyCv(MidtransService $midtrans): void
    {
        $pkg = Order::packages()['cv_ats'];
        $this->processOrder($pkg, $midtrans);
    }

    public function buyBundle(MidtransService $midtrans): void
    {
        $pkg = Order::packages()['bundle_komplit'];
        $this->processOrder($pkg, $midtrans);
    }

    protected function processOrder(array $pkg, MidtransService $midtrans): void
    {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->user->id,
            'item_type' => $pkg['type'],
            'package_key' => $pkg['key'],
            'item_name' => $pkg['name'],
            'credits_amount' => $pkg['credits'],
            'original_amount' => $pkg['original_price'],
            'gross_amount' => $pkg['price'],
            'status' => Order::STATUS_PENDING,
        ]);

        try {
            $midtrans->createSnapToken($order, $this->user);
        } catch (\Throwable $e) {
            // Tetap lanjut di prototype mode
        }

        $this->modalOrder = $order;
        $this->showModal = true;
    }

    public function confirmQuickPayment(): void
    {
        if ($this->modalOrder) {
            Order::grantOrderBenefits($this->modalOrder, ['payment_type' => 'qris_instant']);
            $this->profile->refresh();
            $this->isPaid = true;
            $this->showModal = false;
            $this->dispatch('credits-updated', credits: $this->profile->application_credits);
            $this->dispatch('notify', [
                'message' => 'Pembayaran berhasil dikonfirmasi. CV ATS Profesional Anda telah aktif.',
                'type' => 'success'
            ]);
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->modalOrder = null;
    }

    public function onPaymentSuccess(int $orderId): void
    {
        $order = Order::find($orderId);
        if ($order) {
            Order::grantOrderBenefits($order);
        }

        $this->profile->refresh();
        $this->isPaid = true;
        $this->dispatch('credits-updated', credits: $this->profile->application_credits);
        $this->dispatch('notify', [
            'message' => 'Pembayaran berhasil dikonfirmasi. CV ATS Profesional Anda telah aktif.',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.applicant.cv-generator')->title('Generator CV ATS — NEAR JOB');
    }
}
