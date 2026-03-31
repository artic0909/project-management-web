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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->enum('meeting_type', ['lead', 'order', 'project']);
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            
            // Participants (Stored as JSON arrays of IDs)
            $table->json('assigndev_ids')->nullable();
            $table->json('assignsale_ids')->nullable();
            
            // Authorship (Polymorphic)
            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_type');
            
            $table->string('meeting_title');
            $table->date('meeting_date');
            $table->time('meeting_time');
            $table->text('meeting_description')->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('status')->default('pending'); // pending, completed, canceled
            
            $table->timestamps();

            // Indexing for performance
            $table->index(['created_by_id', 'created_by_type']);
            $table->index('meeting_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
