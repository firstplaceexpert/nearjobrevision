<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id', 'job_listing_id', 'status', 'contact_method', 'application_date',
    ];

    protected $casts = [
        'application_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobListing()
    {
        return $this->belongsTo(JobListing::class);
    }

    public static function statuses(): array
    {
        return [
            'menunggu'    => 'Menunggu Respons',
            'dihubungi'   => 'Sudah Dihubungi',
            'interview'   => 'Interview',
            'diterima'    => 'Diterima',
            'tidak_lolos' => 'Tidak Lolos',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }
}
