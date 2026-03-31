<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    protected $fillable = [
        'lead_id', 'notes', 
        'created_by', 'created_by_type',
        'updated_by', 'updated_by_type'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function createdBy()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by');
    }

    public function updatedBy()
    {
        return $this->morphTo(null, 'updated_by_type', 'updated_by');
    }
}
