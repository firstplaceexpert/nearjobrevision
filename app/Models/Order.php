<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'item_type',
        'package_key',
        'item_name',
        'credits_amount',
        'original_amount',
        'gross_amount',
        'status',
        'snap_token',
        'payment_type',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'credits_amount' => 'integer',
        'original_amount' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_SETTLEMENT = 'settlement';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_EXPIRE = 'expire';
    public const STATUS_FAILURE = 'failure';

    /**
     * Definisi paket harga promo & bundling sesuai Business Model Canvas (BMC)
     */
    public static function packages(): array
    {
        return [
            'starter' => [
                'key' => 'starter',
                'type' => 'credit_topup',
                'name' => 'Peluang Cepat (1 Lamaran)',
                'credits' => 1,
                'original_price' => 15000,
                'price' => 5999,
                'badge' => 'Hemat 60%',
                'highlight' => false,
                'description' => 'Pas untuk melamar ke lowongan impian terdekat saat ini.',
            ],
            'popular' => [
                'key' => 'popular',
                'type' => 'credit_topup',
                'name' => 'Cepat Kerja (5 Lamaran)',
                'credits' => 5,
                'original_price' => 49000,
                'price' => 19999,
                'badge' => 'Paling Populer',
                'highlight' => true,
                'description' => 'Pilihan utama pencari kerja aktif. Biaya hanya Rp3.999/lamaran.',
            ],
            'intensive' => [
                'key' => 'intensive',
                'type' => 'credit_topup',
                'name' => 'Pasti Dapat Kerja (12 Lamaran)',
                'credits' => 12,
                'original_price' => 99000,
                'price' => 39999,
                'badge' => 'Super Hemat',
                'highlight' => false,
                'description' => 'Hanya Rp3.333/lamaran. Solusi hemat untuk intensif melamar.',
            ],
            'cv_ats' => [
                'key' => 'cv_ats',
                'type' => 'cv_generator',
                'name' => 'Generator CV ATS Profesional',
                'credits' => 0,
                'original_price' => 39000,
                'price' => 14999,
                'badge' => 'Standar Industri',
                'highlight' => false,
                'description' => 'Format teruji lolos Applicant Tracking System, langsung jadi dari profil Anda.',
            ],
            'bundle_komplit' => [
                'key' => 'bundle_komplit',
                'type' => 'bundle',
                'name' => 'Paket Siap Kerja All-in-One (CV ATS + 5 Lamaran)',
                'credits' => 5,
                'original_price' => 65000,
                'price' => 24999,
                'badge' => 'Paket Komplit Rekomendasi',
                'highlight' => true,
                'description' => 'Dapat CV ATS Resmi + 5 Kuota Lamaran Prioritas sekaligus. Hemat Rp40.000!',
            ],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_SETTLEMENT;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public static function generateOrderNumber(): string
    {
        return 'NJ-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
    }

    public static function grantOrderBenefits(Order $order, array $paymentDetails = []): bool
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($order, $paymentDetails) {
            $lockedOrder = self::where('id', $order->id)->lockForUpdate()->first();
            if (!$lockedOrder) {
                return false;
            }

            if ($lockedOrder->isPaid()) {
                return true;
            }

            $lockedOrder->update([
                'status' => self::STATUS_SETTLEMENT,
                'payment_type' => $paymentDetails['payment_type'] ?? ($lockedOrder->payment_type ?: 'midtrans'),
                'payment_details' => $paymentDetails ?: $lockedOrder->payment_details,
                'paid_at' => now(),
            ]);

            $user = $lockedOrder->user;
            $profile = $user?->applicantProfile;
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
                            'package' => $lockedOrder->package_key,
                        ],
                    ]);
                }
            }

            return true;
        });
    }
}
