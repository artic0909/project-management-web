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
            $table->time('lunch_from', 0)->nullable()->after('check_out_time');
            $table->time('lunch_to', 0)->nullable()->after('lunch_from');
            $table->integer('total_break_seconds')->default(0)->after('lunch_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['lunch_from', 'lunch_to', 'total_break_seconds']);
        });
    }
};
