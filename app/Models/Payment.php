<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_no', 'order_id', 'transaction_date', 'amount', 'payment_method',
        'transaction_id', 'screenshot', 'notes', 'status_id',
        'created_by', 'created_by_type'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (empty($payment->invoice_no) || strlen($payment->invoice_no) !== 10 || !preg_match('/^[0-9]{10}$/', $payment->invoice_no)) {
                do {
                    $code = (string)random_int(1000000000, 9999999999);
                } while (static::where('invoice_no', $code)->exists());
                    $payment->invoice_no = $code;
            }
        });
    }

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
