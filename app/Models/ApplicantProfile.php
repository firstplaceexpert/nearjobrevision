<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantProfile extends Model
{
    protected $fillable = [
        'user_id', 'photo', 'cover_picture', 'whatsapp',
        'education_level', 'education_institution', 'field_of_study',
        'work_experience', 'skills', 'salary_expectation',
        'contact_email', 'city', 'latitude', 'longitude',
        'is_active', 'application_credits', 'cv_generated', 'cv_data',
    ];

    protected $casts = [
        'skills'   => 'array',
        'cv_data'  => 'array',
        'is_active' => 'boolean',
        'cv_generated' => 'boolean',
    ];

    public function getPhotoUrlAttribute(): string
    {
        if (!$this->photo) return '';
        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }
        return asset('storage/' . $this->photo);
    }

    public function getCoverPictureUrlAttribute(): string
    {
        if (!$this->cover_picture) return '';
        if (str_starts_with($this->cover_picture, 'http://') || str_starts_with($this->cover_picture, 'https://')) {
            return $this->cover_picture;
        }
        return asset('storage/' . $this->cover_picture);
    }

    public function getProfilePictureAttribute(): ?string
    {
        return $this->photo;
    }

    public function setProfilePictureAttribute($value): void
    {
        $this->attributes['photo'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete(): bool
    {
        return !empty($this->education_level)
            && !empty($this->education_institution)
            && !empty($this->city);
    }

    public function hasCredits(): bool
    {
        return $this->application_credits > 0;
    }

    public function deductCredit(): bool
    {
        if ($this->application_credits <= 0) return false;
        $this->decrement('application_credits');
        return true;
    }

    public static function educationLevels(): array
    {
        return [
            'sd'  => 'SD',
            'smp' => 'SMP',
            'sma' => 'SMA / SMK',
            'd3'  => 'D3 (Diploma)',
            's1'  => 'S1 (Sarjana)',
            's2'  => 'S2 (Magister)',
            's3'  => 'S3 (Doktor)',
        ];
    }
}
