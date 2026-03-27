<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientFeedback extends Model
{
    protected $fillable = ['project_id', 'rating', 'feedback'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
