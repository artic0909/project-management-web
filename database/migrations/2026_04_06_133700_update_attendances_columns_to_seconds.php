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
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('late_seconds')->default(0)->after('late_minutes');
            $table->integer('total_seconds')->default(0)->after('total_minutes');
        });

        // Optional: Migrate existing minutes to seconds
        \DB::table('attendances')->update([
            'late_seconds' => \DB::raw('late_minutes * 60'),
            'total_seconds' => \DB::raw('total_minutes * 60'),
        ]);

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['late_minutes', 'total_minutes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('late_minutes')->default(0)->after('status');
            $table->integer('total_minutes')->default(0)->after('late_minutes');
        });

        \DB::table('attendances')->update([
            'late_minutes' => \DB::raw('late_seconds / 60'),
            'total_minutes' => \DB::raw('total_seconds / 60'),
        ]);

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['late_seconds', 'total_seconds']);
        });
    }
};
