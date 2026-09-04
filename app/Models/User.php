<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'nik', 'whatsapp', 'date_of_birth',
    ];

    protected $hidden = ['password', 'remember_token', 'nik'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth'     => 'date',
        'password'          => 'hashed',
    ];

    public function isApplicant(): bool { return $this->role === 'applicant'; }
    public function isCompany(): bool   { return $this->role === 'company'; }

    public function applicantProfile() { return $this->hasOne(ApplicantProfile::class); }
    public function company()          { return $this->hasOne(Company::class); }
    public function applications()     { return $this->hasMany(Application::class); }
    public function orders()           { return $this->hasMany(Order::class); }

    public function getAgeAttribute(): int
    {
        if (!$this->date_of_birth) return 0;
        return $this->date_of_birth->age;
    }
}
