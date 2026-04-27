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
        \DB::statement("ALTER TABLE supports MODIFY COLUMN status ENUM('pending', 'active', 'review', 'replied', 'closed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("ALTER TABLE supports MODIFY COLUMN status ENUM('pending', 'review', 'replied', 'closed') DEFAULT 'pending'");
    }
};
