<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskAssign extends Model
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(ProjectTask::class, 'task_id');
    }

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }
}
