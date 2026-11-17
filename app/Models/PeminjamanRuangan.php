<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanRuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'ruangan_id',
        'nama_peminjam',
        'nim_nip',
        'prodi',
        'keperluan',
        'tgl_pinjam',
        'jam_mulai',
        'jam_selesai',
        'status',
        'catatan',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }
}
