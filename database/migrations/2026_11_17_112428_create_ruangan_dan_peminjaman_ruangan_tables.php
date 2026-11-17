<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ruangan')->unique();
            $table->string('nama_ruangan');
            $table->string('lokasi')->nullable();
            $table->integer('kapasitas')->default(0);
            $table->text('fasilitas')->nullable();
            $table->enum('status', ['Tersedia', 'Perbaikan', 'Tidak Aktif'])->default('Tersedia');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        Schema::create('peminjaman_ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('ruangan_id')->constrained('ruangans')->cascadeOnDelete();
            $table->string('nama_peminjam');
            $table->string('nim_nip');
            $table->string('prodi');
            $table->string('keperluan');
            $table->date('tgl_pinjam');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Selesai'])->default('Menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_ruangans');
        Schema::dropIfExists('ruangans');
    }
};
