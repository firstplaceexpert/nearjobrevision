<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user_id', 'owner_name', 'nik', 'whatsapp',
        'company_name', 'business_field', 'nib',
        'address', 'city', 'latitude', 'longitude',
        'contact_email', 'contact_method', 'agreed_to_terms',
    ];

    protected $hidden = ['nik'];

    protected $casts = [
        'agreed_to_terms' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobListings()
    {
        return $this->hasMany(JobListing::class);
    }

    public function isVerified(): bool
    {
        return !empty($this->owner_name) && !empty($this->company_name) && $this->agreed_to_terms;
    }
}
