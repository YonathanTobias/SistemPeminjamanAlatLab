<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanLabDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanLab::class, 'peminjaman_lab_id');
    }

    public function alat()
    {
        return $this->belongsTo(AlatLab::class, 'alat_lab_id');
    }
}
