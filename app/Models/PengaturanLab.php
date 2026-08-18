<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanLab extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_labs';
    protected $guarded = ['id'];

    public static function getPengaturan()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'nama_sistem'       => 'Sistem Peminjaman Alat Lab',
                'nama_prodi'        => 'Prodi S1 Keperawatan',
                'nama_institusi'    => 'STIKES Panti Waluya Malang',
                'unit_laboratorium' => 'Unit Laboratorium Keperawatan & Kesehatan',
                'kepala_lab'        => 'Ns. Wening Prabawati, M.Kep.',
                'nip_kepala_lab'    => '198205142010122001',
                'alamat_institusi'  => 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117',
                'kontak_lab'        => '(0341) 369003',
                'logo_path'         => 'images/logo-stikes.png',
            ]
        );
    }
}
