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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->onDelete('set null');
            $table->string('company_name');
            $table->string('client_name');
            $table->json('emails')->nullable();
            $table->json('phones')->nullable();
            $table->string('domain_name')->nullable();
            $table->foreignId('service_id')->constrained('services');
            $table->decimal('order_value', 15, 2);
            $table->foreignId('payment_terms_id')->nullable()->constrained('statuses');
            $table->date('delivery_date')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('full_address')->nullable();
            $table->foreignId('status_id')->constrained('statuses');
            
            // Marketing fields
            $table->boolean('is_marketing')->default(false);
            $table->foreignId('mkt_payment_status_id')->nullable()->constrained('statuses');
            $table->date('mkt_starting_date')->nullable();
            $table->string('plan_name')->nullable();
            $table->string('mkt_username')->nullable();
            $table->string('mkt_password')->nullable();
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_type')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
