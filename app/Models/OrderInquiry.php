<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInquiry extends Model
{
    protected $fillable = [
        'company_name', 'client_name', 'emails', 'phones',
        'domain_name', 'order_value', 'service_ids', 'source_ids',
        'city', 'state', 'zip_code', 'full_address', 'notes',
        'ip_address', 'status'
    ];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
        'service_ids' => 'array',
        'source_ids' => 'array',
    ];
}
