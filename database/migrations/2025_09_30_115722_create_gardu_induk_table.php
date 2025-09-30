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
        if (!Schema::hasTable('gardu_induk')) {
            Schema::create('gardu_induk', function (Blueprint $table) {
                $table->id();
                $table->string('kode_gi', 50)->unique();
                $table->string('nama_gi', 100)->nullable();
                $table->text('alamat')->nullable();
                $table->decimal('koordinat_lat', 10, 6)->nullable();
                $table->decimal('koordinat_long', 10, 6)->nullable();
                $table->string('kabupaten', 100)->nullable();
                $table->string('provinsi', 100)->nullable();
                $table->decimal('tegangan_level_in_kv', 6, 2)->nullable();
                $table->decimal('tegangan_level_out_kv', 6, 2)->nullable();
                $table->integer('jumlah_bay')->nullable();
                $table->decimal('kapasitas_mva', 10, 2)->nullable();
                $table->integer('tahun_operasi')->nullable();
                $table->string('status', 20)->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gardu_induk');
    }
};
