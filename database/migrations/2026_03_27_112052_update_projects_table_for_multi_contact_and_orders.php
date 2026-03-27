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
        Schema::table('projects', function (Blueprint $table) {
            $table->json('emails')->nullable()->after('client_name');
            $table->json('phones')->nullable()->after('emails');
        });

        // Migrate data if needed, then drop old columns
        foreach (DB::table('projects')->get() as $p) {
            DB::table('projects')->where('id', $p->id)->update([
                'emails' => json_encode([$p->email]),
                'phones' => json_encode([$p->phone]),
            ]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('email')->nullable()->after('client_name');
            $table->string('phone')->nullable()->after('email');
            $table->dropColumn(['emails', 'phones']);
        });
    }
};
