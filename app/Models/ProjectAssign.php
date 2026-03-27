<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAssign extends Model
{
    protected $fillable = ['project_id', 'assigned_to', 'type'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class, 'assigned_to');
    }
}
