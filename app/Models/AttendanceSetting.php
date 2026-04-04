<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    protected $fillable = [
        'dev_checkin_time',
        'dev_checkout_time',
        'sale_checkin_time',
        'sale_checkout_time',
        'grace_period_minutes',
    ];
}
