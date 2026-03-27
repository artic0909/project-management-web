<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientFeedback extends Model
{
    protected $fillable = [
        'project_id', 'status', 'last_update_date', 'feedback_summary', 'internal_notes', 'rating', 'feedback'
    ];

    protected $casts = [
        'last_update_date' => 'date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
