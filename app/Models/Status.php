<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'status_id');
    }
}
