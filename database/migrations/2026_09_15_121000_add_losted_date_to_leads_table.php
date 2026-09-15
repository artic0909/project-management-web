<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->timestamp('losted_date')->nullable()->after('is_losted');
        });

        // Backfill existing lost leads with their updated_at timestamp
        DB::table('leads')
            ->where('is_losted', 1)
            ->whereNull('losted_date')
            ->update(['losted_date' => DB::raw('updated_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('losted_date');
        });
    }
};
