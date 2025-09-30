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
        // Drop FK from sensors to transformers, then drop tables
        // Drop FK by known constraint name if present
        try {
            DB::statement('ALTER TABLE sensors DROP FOREIGN KEY sensors_ibfk_1');
        } catch (\Throwable $e) {
            // ignore if not exists
        }
        // Also try the Laravel default naming just in case
        try {
            Schema::table('sensors', function (Blueprint $table) {
                $table->dropForeign('sensors_transformer_id_foreign');
            });
        } catch (\Throwable $e) {
            // ignore
        }

        if (Schema::hasTable('transformers')) {
            Schema::drop('transformers');
        }
        if (Schema::hasTable('locations')) {
            Schema::drop('locations');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: not recreating dropped tables here
    }
};
