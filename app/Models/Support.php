<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = [
        'ticket_no',
        'company_name',
        'your_name',
        'email',
        'phone',
        'domain_name',
        'subject',
        'priority',
        'message',
        'attachment',
        'ip_address',
        'status',
    ];

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
