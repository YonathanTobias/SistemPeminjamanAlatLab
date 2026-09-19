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
                'nama_prodi'        => 'Prodi S1 Keperawatan & Profesi Ners',
                'nama_institusi'    => 'STIKES Panti Waluya Malang',
                'unit_laboratorium' => 'Unit Laboratorium Keperawatan Medikal Bedah & OSCE Center',
                'kepala_lab'        => 'Ns. Wening Prabawati, M.Kep.',
                'nip_kepala_lab'    => '198205142010122001',
                'alamat_institusi'  => 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117',
                'kontak_lab'        => '(0341) 369003',
                'logo_path'         => 'images/logo-stikes.png',
            ]
        );
    }

    public function getThemeColorsAttribute()
    {
        $prodi = strtolower($this->nama_prodi ?? '');
        
        // 1. S1 Keperawatan & Profesi Ners -> VIBRANT SUNSET ORANGE
        if (str_contains($prodi, 'keperawatan') || str_contains($prodi, 'ners')) {
            return [
                'name' => 'orange',
                'primary' => '#ea580c',
                'primary_dark' => '#c2410c',
                'primary_light' => '#fff7ed',
                'gradient' => 'linear-gradient(135deg, #431407 0%, #7c2d12 35%, #c2410c 70%, #ea580c 100%)',
                'navbar_bg' => 'rgba(38, 14, 6, 0.96)',
                'navbar_border' => 'rgba(234, 88, 12, 0.25)',
                'badge_soft' => 'rgba(234, 88, 12, 0.15)',
            ];
        }
        
        // 2. S1 Farmasi -> BOTANICAL EMERALD GREEN
        if (str_contains($prodi, 'farmasi')) {
            return [
                'name' => 'green',
                'primary' => '#059669',
                'primary_dark' => '#047857',
                'primary_light' => '#ecfdf5',
                'gradient' => 'linear-gradient(135deg, #022c22 0%, #064e3b 35%, #047857 70%, #059669 100%)',
                'navbar_bg' => 'rgba(4, 30, 24, 0.96)',
                'navbar_border' => 'rgba(16, 185, 129, 0.25)',
                'badge_soft' => 'rgba(16, 185, 129, 0.15)',
            ];
        }

        // 3. D4 MIK -> ROYAL PURPLE
        if (str_contains($prodi, 'mik') || str_contains($prodi, 'informasi kesehatan') || str_contains($prodi, 'rekam medis')) {
            return [
                'name' => 'purple',
                'primary' => '#7c3aed',
                'primary_dark' => '#6d28d9',
                'primary_light' => '#faf5ff',
                'gradient' => 'linear-gradient(135deg, #1e0a3c 0%, #3b0764 35%, #5b21b6 70%, #7c3aed 100%)',
                'navbar_bg' => 'rgba(24, 8, 43, 0.96)',
                'navbar_border' => 'rgba(124, 58, 237, 0.25)',
                'badge_soft' => 'rgba(124, 58, 237, 0.15)',
            ];
        }

        // Default: Sky Blue
        return [
            'name' => 'blue',
            'primary' => '#0284c7',
            'primary_dark' => '#0369a1',
            'primary_light' => '#e0f2fe',
            'gradient' => 'linear-gradient(135deg, #082f49 0%, #0369a1 40%, #0284c7 80%, #38bdf8 100%)',
            'navbar_bg' => 'rgba(15, 23, 42, 0.96)',
            'navbar_border' => 'rgba(2, 132, 199, 0.2)',
            'badge_soft' => 'rgba(2, 132, 199, 0.15)',
        ];
    }

    public function getProdiPrefixAttribute()
    {
        $prodi = strtolower($this->nama_prodi ?? '');
        if (str_contains($prodi, 'keperawatan') || str_contains($prodi, 'ners')) {
            return 'KEP';
        }
        if (str_contains($prodi, 'farmasi')) {
            return 'FAR';
        }
        if (str_contains($prodi, 'mik') || str_contains($prodi, 'informasi kesehatan') || str_contains($prodi, 'rekam medis')) {
            return 'MIK';
        }
        return 'LAB';
    }
}
