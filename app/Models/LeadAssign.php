<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadAssign extends Model
{
    protected $fillable = ['lead_id', 'assigned_to'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'assigned_to');
    }
}
