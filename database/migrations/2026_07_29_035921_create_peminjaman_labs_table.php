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
        Schema::create('peminjaman_labs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // Contoh: TRX-20260818-001
            $table->string('nama_peminjam');            // Nama Mahasiswa / Dosen
            $table->string('nim_nip');                  // NIM atau NIP
            $table->string('prodi');                    // Contoh: D3 Keperawatan, S1 Ilmu Keperawatan
            $table->string('keperluan')->nullable();     // Contoh: Ujian OSCE, Praktikum KMB
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali_rencana');
            $table->dateTime('tgl_kembali_realisasi')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Dikembalikan'])->default('Menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('peminjaman_lab_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_lab_id')->constrained('peminjaman_labs')->cascadeOnDelete();
            $table->foreignId('alat_lab_id')->constrained('alat_labs')->cascadeOnDelete();
            $table->integer('jumlah_pinjam')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_lab_details');
        Schema::dropIfExists('peminjaman_labs');
    }
};
