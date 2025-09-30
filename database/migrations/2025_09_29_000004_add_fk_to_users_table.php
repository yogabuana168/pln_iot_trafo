<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'position_id')) {
                $table->unsignedBigInteger('position_id')->nullable()->after('position');
            }
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'user_group_id')) {
                $table->unsignedBigInteger('user_group_id')->nullable()->after('group_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try { $table->dropForeign(['position_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['department_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['user_group_id']); } catch (\Throwable $e) {}
            try { $table->dropColumn(['position_id','department_id','user_group_id']); } catch (\Throwable $e) {}
        });
    }
};


