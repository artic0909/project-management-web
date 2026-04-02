<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add missing address fields
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'state')) {
                $table->string('state')->nullable()->after('address');
            }
            if (!Schema::hasColumn('leads', 'zip_code')) {
                $table->string('zip_code', 20)->nullable()->after('state');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'city')) {
                $table->string('city')->nullable()->after('full_address');
            }
            if (!Schema::hasColumn('projects', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('projects', 'zip_code')) {
                $table->string('zip_code', 20)->nullable()->after('state');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('leads', 'zip_code')) {
                $table->dropColumn('zip_code');
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('projects', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('projects', 'zip_code')) {
                $table->dropColumn('zip_code');
            }
        });
    }
};
