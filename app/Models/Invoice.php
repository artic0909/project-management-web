<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no', 'invoice_date', 'due_date', 'client_name', 'client_address',
        'client_gstin', 'place_of_supply', 'items', 'subtotal', 'cgst', 'sgst',
        'igst', 'adjustment', 'total', 'notes', 'bank_details', 'order_id', 'payment_id',
        'sender_name', 'sender_address', 'sender_gstin', 'sender_contact', 'sender_email'
    ];
    
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'items' => 'array',
        'bank_details' => 'array',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}