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
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->morphs('followable'); // followable_id and followable_type
            $table->dateTime('followup_date');
            $table->string('followup_type'); // Calling, Message, Both
            $table->text('calling_note')->nullable();
            $table->text('message_note')->nullable();
            $table->string('status')->default('pending');
            $table->morphs('created_by'); // Admin, Sale, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followups');
    }
};
