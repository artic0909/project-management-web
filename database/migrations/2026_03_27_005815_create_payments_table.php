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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->date('transaction_date');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('screenshot')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('status_id')->constrained(); // References statuses table (type=payment)
            $table->foreignId('created_by');
            $table->string('created_by_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
