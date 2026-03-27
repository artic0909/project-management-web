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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            
            // Basic Information
            $table->string('project_name');
            $table->string('client_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_name')->nullable();
            $table->date('starting_date')->nullable();
            $table->string('plan_name')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->integer('no_of_mail_ids')->default(0);
            $table->string('mail_password')->nullable();
            $table->text('domain_server_book')->nullable();
            $table->text('full_address')->nullable();

            // Website Details
            $table->string('domain_name')->nullable();
            $table->string('hosting_provider')->nullable();
            $table->string('cms_platform')->nullable();
            $table->string('cms_custom')->nullable();
            $table->integer('no_of_pages')->default(0);
            $table->string('website_payment_status')->nullable();
            $table->text('required_features')->nullable();
            $table->text('reference_websites')->nullable();

            // Communication & Tracking
            $table->date('last_update_date')->nullable();
            $table->text('client_feedback_summary')->nullable();
            $table->text('internal_notes')->nullable();

            // Project Timeline
            $table->date('project_start_date')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->string('project_status')->nullable();

            // Financial Fields
            $table->decimal('project_price', 15, 2)->default(0);
            $table->decimal('advance_payment', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->string('financial_payment_status')->nullable();
            $table->string('invoice_number')->nullable();

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
        Schema::dropIfExists('projects');
    }
};
