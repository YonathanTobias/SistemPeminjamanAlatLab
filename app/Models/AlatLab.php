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

    // Relasi ke tabel peminjaman_lab_details
    public function peminjamanDetails()
    {
        return $this->hasMany(PeminjamanLabDetail::class, 'alat_lab_id');
    }
}