<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    protected $fillable = [
        'company_id', 'position', 'description', 'qualifications',
        'city', 'latitude', 'longitude',
        'work_type', 'job_category', 'required_skills', 'min_education',
        'salary_min', 'salary_max', 'work_duration', 'work_hours',
        'contact_method', 'contact_whatsapp', 'contact_email',
        'radius_km', 'quota', 'status',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'salary_min'      => 'integer',
        'salary_max'      => 'integer',
        'quota'           => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getWorkTypeLabelAttribute(): string
    {
        return match($this->work_type) {
            'full_time'  => 'Full-time',
            'part_time'  => 'Part-time',
            'harian'     => 'Harian',
            'kontrak'    => 'Kontrak',
            'internship' => 'Magang',
            default      => $this->work_type,
        };
    }

    public function getSalaryRangeAttribute(): string
    {
        if (!$this->salary_min) return 'Negosiasi';
        $min = 'Rp' . number_format($this->salary_min, 0, ',', '.');
        if ($this->salary_max) {
            $max = 'Rp' . number_format($this->salary_max, 0, ',', '.');
            return "{$min}–{$max}/bulan";
        }
        return "{$min}/bulan";
    }

    public static function workTypes(): array
    {
        return [
            'full_time' => 'Full-time',
            'part_time' => 'Part-time',
            'harian'    => 'Harian',
            'kontrak'   => 'Kontrak',
        ];
    }

    public static function jobCategories(): array
    {
        return [
            'fnb'           => 'F&B (Makanan & Minuman)',
            'retail'        => 'Retail',
            'jasa'          => 'Jasa',
            'produksi'      => 'Produksi',
            'logistik'      => 'Logistik & Gudang',
            'konstruksi'    => 'Konstruksi',
            'administrasi'  => 'Administrasi',
            'teknisi'       => 'Teknisi',
            'lainnya'       => 'Lainnya',
        ];
    }
}
