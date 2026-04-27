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
        $columns = function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('aadhar_card')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('voter_card')->nullable();
            $table->string('bank_account_pic')->nullable();
            $table->text('qualification_details')->nullable();
            $table->json('qualification_attachments')->nullable();
            $table->boolean('kyc_submitted')->default(false);
        };

        Schema::table('sales', $columns);
        Schema::table('developers', $columns);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dropColumns = function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'address', 'profile_image', 'aadhar_card', 'pan_card', 
                'voter_card', 'bank_account_pic', 'qualification_details', 
                'qualification_attachments', 'kyc_submitted'
            ]);
        };

        Schema::table('sales', $dropColumns);
        Schema::table('developers', $dropColumns);
    }
};
