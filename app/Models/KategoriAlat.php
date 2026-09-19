<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAlat extends Model
{
    use HasFactory;

    protected $table = 'kategori_alats';
    protected $guarded = ['id'];

    public function alats()
    {
        return $this->hasMany(AlatLab::class, 'kategori', 'nama_kategori');
    }
}