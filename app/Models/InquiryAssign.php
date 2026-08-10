<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InquiryAssign extends Model
{
    protected $fillable = ['order_inquiry_id', 'assigned_to'];

    public function inquiry()
    {
        return $this->belongsTo(OrderInquiry::class, 'order_inquiry_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'assigned_to');
    }
}
