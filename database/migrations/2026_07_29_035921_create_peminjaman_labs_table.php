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
            $table->foreignId('alat_lab_id')->constrained('alat_labs')->cascadeOnDelete();
            $table->string('nama_peminjam');        // Nama Mahasiswa / Dosen
            $table->string('nim_nip');              // NIM atau NIP
            $table->string('prodi');                // Contoh: D3 Keperawatan, S1 Farmasi
            $table->integer('jumlah_pinjam');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali_rencana');
            $table->date('tgl_kembali_realisasi')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Dikembalikan'])->default('Menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_labs');
    }
};
