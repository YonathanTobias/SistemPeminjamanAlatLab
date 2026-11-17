<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'lokasi',
        'kapasitas',
        'fasilitas',
        'status',
        'gambar',
    ];

    public function peminjamans()
    {
        return $this->hasMany(PeminjamanRuangan::class, 'ruangan_id');
    }
}
