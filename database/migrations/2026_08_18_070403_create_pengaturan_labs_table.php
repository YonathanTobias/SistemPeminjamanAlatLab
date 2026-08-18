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
        Schema::create('pengaturan_labs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sistem')->default('Sistem Peminjaman Alat Lab');
            $table->string('nama_prodi')->default('Prodi S1 Ilmu Keperawatan');
            $table->string('nama_institusi')->default('STIKES Panti Waluya Malang');
            $table->string('unit_laboratorium')->default('Unit Laboratorium Keperawatan & Kesehatan');
            $table->string('kepala_lab')->default('Ns. Wening Prabawati, M.Kep.');
            $table->string('nip_kepala_lab')->default('198205142010122001');
            $table->string('alamat_institusi')->default('Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117');
            $table->string('kontak_lab')->default('(0341) 369003');
            $table->string('logo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_labs');
    }
};
