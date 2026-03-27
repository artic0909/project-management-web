<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('client_feedback', function (Blueprint $table) {
            $table->date('last_update_date')->nullable()->after('project_id');
            $table->text('feedback_summary')->nullable()->after('last_update_date');
            $table->text('internal_notes')->nullable()->after('feedback_summary');
            
            // Clean up old fields if they exist and are redundant
            // $table->dropColumn(['rating', 'feedback']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_feedback', function (Blueprint $table) {
            $table->dropColumn(['last_update_date', 'feedback_summary', 'internal_notes']);
        });
    }
};
