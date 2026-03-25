<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'company', 'contact_person', 'business_type', 'emails', 'phones',
        'address', 'service_need', 'lead_source', 'priority', 'status'
    ];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
    ];

    public function assignments()
    {
        return $this->hasMany(LeadAssign::class);
    }
}
