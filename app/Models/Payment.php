<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'transaction_date', 'amount', 'payment_method',
        'transaction_id', 'screenshot', 'notes', 'status_id',
        'created_by', 'created_by_type'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function createdBy()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by');
    }
}
