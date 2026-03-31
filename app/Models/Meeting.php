<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'assigndev_ids' => 'array',
        'assignsale_ids' => 'array',
        'meeting_date' => 'date',
    ];

    public function createdBy()
    {
        return $this->morphTo();
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the developers as a collection of models
     */
    public function developers()
    {
        return Developer::whereIn('id', $this->assigndev_ids ?? [])->get();
    }

    /**
     * Get the sales team as a collection of models
     */
    public function sales()
    {
        return Sale::whereIn('id', $this->assignsale_ids ?? [])->get();
    }
}
