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
        Schema::create('order_notes', function (Blueprint $table) {
             $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->text('notes');
            
            // Polymorphic attribution for Creator
            $table->unsignedBigInteger('created_by');
            $table->string('created_by_type');
            
            // Polymorphic attribution for Last Updater
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_type')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_notes');
    }
};
