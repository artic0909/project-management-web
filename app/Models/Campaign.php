<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'campaign_id');
    }
}
