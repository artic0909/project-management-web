<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignments()
    {
        return $this->hasMany(ProjectTaskAssign::class, 'task_id');
    }

    public function creator()
    {
        return $this->morphTo(null, 'created_by_type', 'created_by');
    }
}
