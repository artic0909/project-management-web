<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'source_id');
    }
}
