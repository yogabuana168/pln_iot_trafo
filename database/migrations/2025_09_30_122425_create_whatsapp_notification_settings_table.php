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
        if (!Schema::hasTable('whatsapp_notification_settings')) {
            Schema::create('whatsapp_notification_settings', function (Blueprint $table) {
                $table->id();
                // audience: all, group, users
                $table->enum('audience_type', ['all','group','users'])->default('all');
                $table->json('audience_ids')->nullable(); // group ids or user ids
                $table->unsignedBigInteger('template_id')->nullable(); // notification_templates.id
                $table->foreign('template_id')->references('id')->on('notification_templates')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_notification_settings');
    }
};
