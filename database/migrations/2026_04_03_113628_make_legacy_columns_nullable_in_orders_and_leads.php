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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->change();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->change();
            $table->foreignId('source_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable(false)->change();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable(false)->change();
            $table->foreignId('source_id')->nullable(false)->change();
        });
    }
};
