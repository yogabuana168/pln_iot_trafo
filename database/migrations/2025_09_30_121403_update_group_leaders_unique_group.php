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
        Schema::table('group_leaders', function (Blueprint $table) {
            // ensure index uniqueness per group
            $table->unique('group_id', 'group_leaders_group_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('group_leaders', function (Blueprint $table) {
            $table->dropUnique('group_leaders_group_unique');
        });
    }
};
