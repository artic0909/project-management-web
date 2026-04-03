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
            if (!Schema::hasColumn('projects', 'project_code')) {
                $table->string('project_code')->unique()->after('id')->nullable();
            }
            if (!Schema::hasColumn('projects', 'first_name')) {
                $table->string('first_name')->nullable()->after('client_name');
            }
            if (!Schema::hasColumn('projects', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            
            $table->date('order_date_create')->nullable();
            $table->string('domain_provider_name')->nullable();
            $table->decimal('domain_renewal_price', 15, 2)->nullable();
            $table->string('hosting_provider_name')->nullable();
            $table->decimal('hosting_renewal_price', 15, 2)->nullable();
            $table->string('primary_domain_name')->nullable();
            $table->text('extra_features')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'project_code', 'first_name', 'last_name', 
                'order_date_create', 'domain_provider_name', 
                'domain_renewal_price', 'hosting_provider_name', 
                'hosting_renewal_price', 'primary_domain_name', 
                'extra_features'
            ]);
        });
    }
};
