<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->string('trigger_sensor')->nullable()->after('type'); // e.g., R, S, T, etc.
            $table->string('trigger_condition')->nullable()->after('trigger_sensor'); // equals, not_equals, changed
            $table->string('trigger_value')->nullable()->after('trigger_condition'); // e.g., 0 or 1
        });
    }

    public function down(): void
    {
        Schema::table('notification_templates', function (Blueprint $table) {
            $table->dropColumn(['trigger_sensor', 'trigger_condition', 'trigger_value']);
        });
    }
};


