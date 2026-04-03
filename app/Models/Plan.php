<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'plan_id');
    }
}
