<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'support_id',
        'message_reply',
        'status',
    ];

    public function support()
    {
        return $this->belongsTo(Support::class);
    }
}
