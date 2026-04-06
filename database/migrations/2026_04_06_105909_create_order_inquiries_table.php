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
        Schema::create('order_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('client_name');
            $table->json('emails');
            $table->json('phones');
            $table->string('domain_name')->nullable();
            $table->decimal('order_value', 15, 2)->nullable();
            $table->json('service_ids')->nullable();
            $table->json('source_ids')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('full_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_inquiries');
    }
};
