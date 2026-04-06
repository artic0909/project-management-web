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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('your_name');
            $table->string('domain_name')->nullable();
            $table->string('subject');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->string('ip_address')->nullable();
            $table->enum('status', ['pending', 'review', 'replied', 'closed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};
