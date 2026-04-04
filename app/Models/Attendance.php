<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'date',
        'check_in_time',
        'check_out_time',
        'check_in_screenshot',
        'check_out_screenshot',
        'status',
        'is_checked_in',
        'note',
        'late_minutes',
        'total_minutes',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'date' => 'date',
        'is_checked_in' => 'boolean',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
