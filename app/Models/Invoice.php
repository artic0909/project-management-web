<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no', 'invoice_date', 'due_date', 'client_name', 'client_address',
        'client_gstin', 'place_of_supply', 'items', 'subtotal', 'cgst', 'sgst',
        'igst', 'adjustment', 'total', 'status', 'notes', 'bank_details', 'order_id', 'payment_id',
        'sender_name', 'sender_address', 'sender_gstin', 'sender_contact', 'sender_email',
        'created_by', 'created_by_type'
    ];
    
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'items' => 'array',
        'bank_details' => 'array',
    ];
    
    protected static function booted()
    {
        static::creating(function ($invoice) {
            if (empty($invoice->invoice_no) || strlen($invoice->invoice_no) !== 10 || !preg_match('/^[0-9]{10}$/', $invoice->invoice_no)) {
                do {
                    $code = (string)random_int(1000000000, 9999999999);
                } while (static::where('invoice_no', $code)->exists());
                $invoice->invoice_no = $code;
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}