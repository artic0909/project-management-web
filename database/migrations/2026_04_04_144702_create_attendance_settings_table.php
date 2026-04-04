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
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->time('dev_checkin_time')->default('10:00:00');
            $table->time('dev_checkout_time')->default('19:00:00');
            $table->time('sale_checkin_time')->default('10:00:00');
            $table->time('sale_checkout_time')->default('19:00:00');
            $table->integer('grace_period_minutes')->default(15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
