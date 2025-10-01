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
        Schema::table('transformator', function (Blueprint $table) {
            $table->unsignedBigInteger('gardu_induk_id')->nullable()->after('type_id');
            $table->foreign('gardu_induk_id')->references('id')->on('gardu_induk')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transformator', function (Blueprint $table) {
            $table->dropForeign(['gardu_induk_id']);
            $table->dropColumn('gardu_induk_id');
        });
    }
};
