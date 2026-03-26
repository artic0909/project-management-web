<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAssign extends Model
{
    protected $fillable = ['order_id', 'assigned_to'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'assigned_to');
    }
}
