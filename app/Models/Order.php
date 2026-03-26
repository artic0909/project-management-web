<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'lead_id', 'company_name', 'client_name', 'emails', 'phones',
        'domain_name', 'service_id', 'order_value', 'payment_terms_id',
        'delivery_date', 'city', 'state', 'zip_code', 'full_address',
        'status_id', 'is_marketing', 'mkt_payment_status_id',
        'mkt_starting_date', 'plan_name', 'mkt_username', 'mkt_password',
    ];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
        'is_marketing' => 'boolean',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function paymentTerms()
    {
        return $this->belongsTo(Status::class, 'payment_terms_id');
    }

    public function mktPaymentStatus()
    {
        return $this->belongsTo(Status::class, 'mkt_payment_status_id');
    }

    public function assignments()
    {
        return $this->hasMany(OrderAssign::class);
    }
}
