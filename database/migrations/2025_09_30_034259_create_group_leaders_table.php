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
        Schema::create('group_leaders', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            
            // Unique constraint: one leader per group
            $table->unique('group_id');
            
            // Add indexes for performance
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_leaders');
    }
};
