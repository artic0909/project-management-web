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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            
            // Client Info
            $table->string('client_name');
            $table->text('client_address')->nullable();
            $table->string('client_gstin')->nullable();
            $table->string('place_of_supply')->nullable();
            
            // Financial Info
            $table->json('items'); // Array of objects: {desc, hsn, qty, rate, amount}
            $table->decimal('subtotal', 15, 2);
            $table->decimal('cgst', 15, 2)->default(0);
            $table->decimal('sgst', 15, 2)->default(0);
            $table->decimal('igst', 15, 2)->default(0);
            $table->decimal('adjustment', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            
            $table->text('notes')->nullable();
            $table->json('bank_details')->nullable();
            
            // Relations
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
