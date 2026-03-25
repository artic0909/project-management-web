<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'company', 'contact_person', 'business_type', 'emails', 'phones',
        'address', 'service_id', 'source_id', 'status_id', 'campaign_id',
        'priority', 'created_by', 'created_by_type',
    ];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
    ];

    public function assignments()
    {
        return $this->hasMany(LeadAssign::class);
    }

    public function createdBy()
    {
        return $this->morphTo('created_by', 'created_by_type', 'created_by');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
