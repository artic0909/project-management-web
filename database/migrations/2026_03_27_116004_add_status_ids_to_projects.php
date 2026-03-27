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
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('project_status_id')->nullable()->after('project_status');
            $table->unsignedBigInteger('payment_status_id')->nullable()->after('payment_status');
            
            $table->foreign('project_status_id')->references('id')->on('statuses')->onDelete('set null');
            $table->foreign('payment_status_id')->references('id')->on('statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['project_status_id']);
            $table->dropForeign(['payment_status_id']);
            $table->dropColumn(['project_status_id', 'payment_status_id']);
        });
    }
};
