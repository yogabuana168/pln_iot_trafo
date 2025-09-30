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
        // Recreate transformator table
        if (Schema::hasTable('transformator')) {
            Schema::drop('transformator');
        }

        Schema::create('transformator', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aset', 50)->unique();
            $table->string('nomor_seri', 100)->nullable();
            $table->string('merk', 100)->nullable();
            $table->integer('tahun_operasi')->nullable();

            $table->string('lokasi', 255)->nullable();
            $table->decimal('koordinat_lat', 10, 6)->nullable();
            $table->decimal('koordinat_long', 10, 6)->nullable();
            $table->string('penyulang', 100)->nullable();
            $table->string('gardu', 100)->nullable();

            $table->string('status', 20)->nullable();
            $table->text('keterangan')->nullable();

            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('type_transformator')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transformator');
    }
};
