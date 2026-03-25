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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('contact_person');
            $table->string('business_type')->nullable();
            $table->json('emails')->nullable();
            $table->json('phones')->nullable();
            $table->text('address')->nullable();
            
            // IDs for related tables
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('restrict');
            $table->foreignId('source_id')->nullable()->constrained('sources')->onDelete('restrict');
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->onDelete('restrict');
            $table->foreignId('status_id')->nullable()->constrained('statuses')->onDelete('restrict');
            
            $table->string('priority')->nullable();
            
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
        Schema::dropIfExists('leads');
    }
};
