<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatLab extends Model
{
    use HasFactory;

    protected $table = 'alat_labs';

    protected $fillable = [
        'kode_alat',
        'nama_alat',
        'kategori',
        'stok_total',
        'stok_tersedia',
        'kondisi',
        'gambar',
    ];

    // Relasi ke tabel peminjaman_lab_details
    public function peminjamanDetails()
    {
        return $this->hasMany(PeminjamanLabDetail::class, 'alat_lab_id');
    }

    /**
     * Sinkronisasi otomatis stok_tersedia berdasarkan stok_total dikurangi transaksi yang sedang dipinjam (Disetujui).
     */
    public function syncStok()
    {
        $sedangDipinjam = PeminjamanLabDetail::where('alat_lab_id', $this->id)
            ->whereHas('peminjaman', function($q) {
                $q->where('status', 'Disetujui');
            })
            ->sum('jumlah_pinjam');

        $this->stok_tersedia = max(0, $this->stok_total - $sedangDipinjam);
        $this->save();

        return $this->stok_tersedia;
    }
}