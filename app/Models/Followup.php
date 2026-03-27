<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    protected $fillable = [
        'followable_id', 'followable_type', 'followup_date', 
        'followup_type', 'calling_note', 'message_note', 
        'status', 'created_by_id', 'created_by_type'
    ];

    protected $casts = [
        'followup_date' => 'datetime',
    ];

    public function followable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by_id');
    }
}
