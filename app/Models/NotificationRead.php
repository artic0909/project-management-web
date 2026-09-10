<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRead extends Model
{
    protected $table = 'notification_reads';

    protected $fillable = [
        'user_type',
        'user_id',
        'item_type',
        'item_id',
    ];
}
