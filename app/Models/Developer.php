<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class Developer extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $guard = 'developer';

    protected $fillable = [
        'name',
        'designation',
        'email',
        'password',
        'created_by',
        'created_by_type',
    ];

    public function createdBy()
    {
        return $this->morphTo('created_by', 'created_by_type', 'created_by');
    }

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_assigns', 'assigned_to', 'project_id');
    }

    public function tasks()
    {
        return $this->belongsToMany(ProjectTask::class, 'project_task_assigns', 'developer_id', 'task_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function attendances()
    {
        return $this->morphMany(\App\Models\Attendance::class, 'user');
    }
}
