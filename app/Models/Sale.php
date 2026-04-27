<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Sale extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $guard = 'sale';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'profile_image',
        'aadhar_card',
        'pan_card',
        'voter_card',
        'bank_account_pic',
        'qualification_details',
        'qualification_attachments',
        'kyc_submitted',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'qualification_attachments' => 'array',
            'kyc_submitted' => 'boolean',
        ];
    }

    public function assignments()
    {
        return $this->hasMany(LeadAssign::class, 'assigned_to');
    }
    public function attendances()
    {
        return $this->morphMany(\App\Models\Attendance::class, 'user');
    }
}
