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
        'stok_total',
        'stok_tersedia',
        'kondisi',
    ];

    // Relasi ke tabel peminjaman_labs
    public function peminjaman()
    {
        return $this->hasMany(PeminjamanLab::class, 'alat_lab_id');
    }
}