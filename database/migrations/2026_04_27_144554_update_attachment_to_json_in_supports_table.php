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
        // Convert existing attachment strings to valid JSON arrays
        $supports = \DB::table('supports')->whereNotNull('attachment')->get();
        foreach ($supports as $support) {
            $json = json_encode([$support->attachment]);
            \DB::table('supports')->where('id', $support->id)->update(['attachment' => $json]);
        }
        \DB::statement("ALTER TABLE supports MODIFY attachment JSON NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("ALTER TABLE supports MODIFY attachment VARCHAR(255) NULL");
        
        $supports = \DB::table('supports')->whereNotNull('attachment')->get();
        foreach ($supports as $support) {
            $array = json_decode($support->attachment, true);
            $val = (is_array($array) && count($array) > 0) ? $array[0] : null;
            \DB::table('supports')->where('id', $support->id)->update(['attachment' => $val]);
        }
    }
};
