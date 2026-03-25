<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'service_id');
    }
}
