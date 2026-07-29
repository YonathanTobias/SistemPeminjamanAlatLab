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
            Schema::create('alat_labs', function (Blueprint $table) {
                $table->id();
                $table->string('kode_alat')->unique(); // Contoh: LAB-001
                $table->string('nama_alat');           // Contoh: Stetoskop, Tensimeter
                $table->integer('stok_total');
                $table->integer('stok_tersedia');
                $table->string('kondisi')->default('Baik');
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat_labs');
    }
};
