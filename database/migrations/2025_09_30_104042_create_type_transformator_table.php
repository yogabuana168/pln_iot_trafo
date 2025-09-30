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
        if (!Schema::hasTable('type_transformator')) {
            Schema::create('type_transformator', function (Blueprint $table) {
                $table->id();
                $table->integer('kapasitas_kva');
                $table->decimal('tegangan_primer_kv', 5, 2);
                $table->integer('tegangan_sekunder_v');
                $table->integer('frekuensi_hz')->default(50);
                $table->decimal('impedansi_percent', 5, 2)->nullable();
                $table->integer('jumlah_phasa');
                $table->string('pendingin', 50)->nullable();
                $table->string('jenis_konstruksi', 50)->nullable();
                $table->string('isolasi', 50)->nullable();
                $table->string('fco', 50)->nullable();
                $table->string('arrester', 50)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_transformator');
    }
};
