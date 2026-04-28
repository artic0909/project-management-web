<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'attachments',
        'created_by',
        'created_by_type',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function createdBy()
    {
        return $this->morphTo('createdBy', 'created_by_type', 'created_by');
    }
}
