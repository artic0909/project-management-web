<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $projects = DB::table('projects')->get();

        foreach ($projects as $project) {
            if ($project->last_update_date || $project->client_feedback_summary || $project->internal_notes) {
                DB::table('client_feedback')->insert([
                    'project_id' => $project->id,
                    'last_update_date' => $project->last_update_date,
                    'feedback_summary' => $project->client_feedback_summary,
                    'internal_notes' => $project->internal_notes,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to undo but typically we don't want to delete data
    }
};
