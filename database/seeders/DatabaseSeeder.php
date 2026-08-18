<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AlatLab;
use App\Models\PeminjamanLab;
use App\Models\PeminjamanLabDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Laboran
        User::firstOrCreate(
            ['email' => 'laboran@stikes.ac.id'],
            [
                'name' => 'Laboran STIKES Panti Waluya',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Master Data Peralatan Laboratorium Keperawatan STIKES Panti Waluya
        $alats = [
            // Kategori KDM & Tanda-Tanda Vital
            [
                'kode_alat' => 'KDM-001',
                'nama_alat' => 'Stetoskop Dewasa Littmann Classic III',
                'stok_total' => 20,
                'stok_tersedia' => 17,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'KDM-002',
                'nama_alat' => 'Stetoskop Pediatrik / Anak Riester',
                'stok_total' => 10,
                'stok_tersedia' => 10,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'KDM-003',
                'nama_alat' => 'Tensimeter Aneroid Jarum Riester & Manset',
                'stok_total' => 15,
                'stok_tersedia' => 13,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'KDM-004',
                'nama_alat' => 'Tensimeter Digital Omron HEM-7120',
                'stok_total' => 12,
                'stok_tersedia' => 11,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'KDM-005',
                'nama_alat' => 'Pulse Oximeter Fingertip OMRON',
                'stok_total' => 10,
                'stok_tersedia' => 10,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'KDM-006',
                'nama_alat' => 'Termometer Inframerah Forehead Non-Contact',
                'stok_total' => 8,
                'stok_tersedia' => 8,
                'kondisi' => 'Baik',
            ],

            // Kategori Manikin & Simulator Keperawatan
            [
                'kode_alat' => 'SIM-001',
                'nama_alat' => 'Manikin Full Body Pasien Dewasa (Nursing Skills Trainer)',
                'stok_total' => 4,
                'stok_tersedia' => 3,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SIM-002',
                'nama_alat' => 'Manikin Resusitasi Jantung Paru (CPR/RJP) Dewasa',
                'stok_total' => 5,
                'stok_tersedia' => 4,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SIM-003',
                'nama_alat' => 'Manikin CPR Bayi / Infant CPR Simulator',
                'stok_total' => 3,
                'stok_tersedia' => 3,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SIM-004',
                'nama_alat' => 'Manikin Lengan Injeksi IV & Phlebotomy Trainer',
                'stok_total' => 6,
                'stok_tersedia' => 5,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SIM-005',
                'nama_alat' => 'Manikin Kateterisasi Urin Pria & Wanita 2-in-1',
                'stok_total' => 4,
                'stok_tersedia' => 4,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SIM-006',
                'nama_alat' => 'Manikin Simulator NGT (Nasogastric Tube) & Bilas Lambung',
                'stok_total' => 3,
                'stok_tersedia' => 3,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SIM-007',
                'nama_alat' => 'Manikin Model Perawatan Luka & Ulkus Dekubitus',
                'stok_total' => 2,
                'stok_tersedia' => 2,
                'kondisi' => 'Baik',
            ],

            // Kategori Elektromedis Keperawatan
            [
                'kode_alat' => 'MED-001',
                'nama_alat' => 'Infusion Pump Mindray SK-600I',
                'stok_total' => 6,
                'stok_tersedia' => 5,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'MED-002',
                'nama_alat' => 'Syringe Pump Fresenius Kabi Injectomat',
                'stok_total' => 4,
                'stok_tersedia' => 4,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'MED-003',
                'nama_alat' => 'Suction Pump Portable (Alat Penghisap Lendir)',
                'stok_total' => 4,
                'stok_tersedia' => 3,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'MED-004',
                'nama_alat' => 'Nebulizer Kompresor Omron NE-C28',
                'stok_total' => 8,
                'stok_tersedia' => 8,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'MED-005',
                'nama_alat' => 'Mesin Rekam Jantung EKG 12 Lead Portable',
                'stok_total' => 2,
                'stok_tersedia' => 2,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'MED-006',
                'nama_alat' => 'Glukometer 3-in-1 (Gula Darah, Asam Urat, Kolesterol)',
                'stok_total' => 10,
                'stok_tersedia' => 10,
                'kondisi' => 'Baik',
            ],

            // Kategori Set Instrumen & Rawat Luka (Hecting & GV)
            [
                'kode_alat' => 'SET-001',
                'nama_alat' => 'Set Rawat Luka / Ganti Verband (GV Set) Stainless',
                'stok_total' => 15,
                'stok_tersedia' => 15,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SET-002',
                'nama_alat' => 'Set Hecting / Jahit Luka Keperawatan Minor',
                'stok_total' => 12,
                'stok_tersedia' => 12,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SET-003',
                'nama_alat' => 'Bak Instrumen Stainless Steel Bertutup 509 Besar',
                'stok_total' => 25,
                'stok_tersedia' => 25,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'SET-004',
                'nama_alat' => 'Bengkok / Nierbeken Stainless Steel 20cm',
                'stok_total' => 30,
                'stok_tersedia' => 30,
                'kondisi' => 'Baik',
            ],

            // Kategori Mobilisasi & Perlengkapan Kamar Pasien
            [
                'kode_alat' => 'MOB-001',
                'nama_alat' => 'Kursi Roda Lipat Standar Rumah Sakit',
                'stok_total' => 4,
                'stok_tersedia' => 3,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'MOB-002',
                'nama_alat' => 'Tongkat Ketiak / Kruk Aluminium Sepasang (Ukuran M)',
                'stok_total' => 5,
                'stok_tersedia' => 5,
                'kondisi' => 'Baik',
            ],
            [
                'kode_alat' => 'MOB-003',
                'nama_alat' => 'Walker Lansia Tanpa Roda Aluminium',
                'stok_total' => 3,
                'stok_tersedia' => 2,
                'kondisi' => 'Rusak Ringan',
            ],
            [
                'kode_alat' => 'MOB-004',
                'nama_alat' => 'Tiang Infus Kaki 5 Stainless Steel Beroda',
                'stok_total' => 10,
                'stok_tersedia' => 9,
                'kondisi' => 'Baik',
            ],
        ];

        $alatModels = [];
        foreach ($alats as $alat) {
            $alatModels[$alat['kode_alat']] = AlatLab::create($alat);
        }

        // 3. Sampel Data Transaksi Paket Multi-Alat Keperawatan
        $transaksis = [
            [
                'header' => [
                    'kode_transaksi' => 'TRX-20260815-001',
                    'nama_peminjam' => 'Maria Kristina Putri',
                    'nim_nip' => '202301015',
                    'prodi' => 'D3 Keperawatan',
                    'keperluan' => 'Ujian OSCE Keperawatan Dasar (Pemeriksaan Fisik & TTV)',
                    'tgl_pinjam' => Carbon::now()->subDays(3)->format('Y-m-d'),
                    'tgl_kembali_rencana' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'tgl_kembali_realisasi' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
                    'status' => 'Dikembalikan',
                    'catatan' => 'Peminjaman paket alat OSCE, seluruh alat dikembalikan lengkap dan steril.',
                ],
                'items' => [
                    ['alat_id' => $alatModels['KDM-001']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['KDM-003']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['KDM-005']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['KDM-006']->id, 'qty' => 1],
                ]
            ],
            [
                'header' => [
                    'kode_transaksi' => 'TRX-20260817-002',
                    'nama_peminjam' => 'Ns. Yohanes Bagus, S.Kep., M.Kep.',
                    'nim_nip' => '1988041201',
                    'prodi' => 'S1 Ilmu Keperawatan',
                    'keperluan' => 'Praktikum Demonstrasi KMB I (Pemasangan Terapi Infus & Syringe Pump)',
                    'tgl_pinjam' => Carbon::now()->subDays(1)->format('Y-m-d'),
                    'tgl_kembali_rencana' => Carbon::now()->addDays(1)->format('Y-m-d'),
                    'tgl_kembali_realisasi' => null,
                    'status' => 'Disetujui',
                    'catatan' => 'Digunakan di Ruang Mini Hospital Lab 2.',
                ],
                'items' => [
                    ['alat_id' => $alatModels['SIM-004']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['MED-001']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['MED-002']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['MOB-004']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['SET-003']->id, 'qty' => 1],
                ]
            ],
            [
                'header' => [
                    'kode_transaksi' => 'TRX-20260818-003',
                    'nama_peminjam' => 'Agustina Citra Dewi',
                    'nim_nip' => '202401042',
                    'prodi' => 'D3 Keperawatan',
                    'keperluan' => 'Latihan Mandiri Perawatan Luka & Ganti Balutan (GV)',
                    'tgl_pinjam' => Carbon::now()->format('Y-m-d'),
                    'tgl_kembali_rencana' => Carbon::now()->addDays(1)->format('Y-m-d'),
                    'tgl_kembali_realisasi' => null,
                    'status' => 'Disetujui',
                    'catatan' => 'Praktikum perawatan luka dekubitus kelompok 4.',
                ],
                'items' => [
                    ['alat_id' => $alatModels['SIM-007']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['SET-001']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['SET-004']->id, 'qty' => 1],
                ]
            ],
            [
                'header' => [
                    'kode_transaksi' => 'TRX-20260818-004',
                    'nama_peminjam' => 'Fransiskus Dedi Pratama',
                    'nim_nip' => '202201008',
                    'prodi' => 'Profesi Ners',
                    'keperluan' => 'Persiapan Stase Gawat Darurat (BHD & Resusitasi Jantung Paru)',
                    'tgl_pinjam' => Carbon::now()->format('Y-m-d'),
                    'tgl_kembali_rencana' => Carbon::now()->addDays(2)->format('Y-m-d'),
                    'tgl_kembali_realisasi' => null,
                    'status' => 'Menunggu',
                    'catatan' => 'Meminjam manikin CPR dewasa dan bayi untuk simulasi henti jantung.',
                ],
                'items' => [
                    ['alat_id' => $alatModels['SIM-002']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['SIM-003']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['MED-003']->id, 'qty' => 1],
                ]
            ],
            [
                'header' => [
                    'kode_transaksi' => 'TRX-20260818-005',
                    'nama_peminjam' => 'Theresia Novita Sari',
                    'nim_nip' => '202401021',
                    'prodi' => 'D3 Keperawatan',
                    'keperluan' => 'Praktikum Kateterisasi & Mobilisasi Pasien',
                    'tgl_pinjam' => Carbon::now()->format('Y-m-d'),
                    'tgl_kembali_rencana' => Carbon::now()->addDays(1)->format('Y-m-d'),
                    'tgl_kembali_realisasi' => null,
                    'status' => 'Menunggu',
                    'catatan' => 'Praktikum keperawatan gerontik dan eliminasi.',
                ],
                'items' => [
                    ['alat_id' => $alatModels['SIM-005']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['MOB-001']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['MOB-002']->id, 'qty' => 1],
                ]
            ],
            [
                'header' => [
                    'kode_transaksi' => 'TRX-20260816-006',
                    'nama_peminjam' => 'Benediktus Arya',
                    'nim_nip' => '202301077',
                    'prodi' => 'S1 Ilmu Keperawatan',
                    'keperluan' => 'Pemeriksaan Hemodinamik & EKG 12 Lead',
                    'tgl_pinjam' => Carbon::now()->subDays(2)->format('Y-m-d'),
                    'tgl_kembali_rencana' => Carbon::now()->subDays(1)->format('Y-m-d'),
                    'tgl_kembali_realisasi' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                    'status' => 'Dikembalikan',
                    'catatan' => 'Selesai praktikum EKG, kertas rekam terpasang rapi.',
                ],
                'items' => [
                    ['alat_id' => $alatModels['MED-005']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['KDM-001']->id, 'qty' => 1],
                    ['alat_id' => $alatModels['KDM-004']->id, 'qty' => 1],
                ]
            ],
        ];

        foreach ($transaksis as $trx) {
            $peminjaman = PeminjamanLab::create($trx['header']);
            foreach ($trx['items'] as $item) {
                PeminjamanLabDetail::create([
                    'peminjaman_lab_id' => $peminjaman->id,
                    'alat_lab_id' => $item['alat_id'],
                    'jumlah_pinjam' => $item['qty'],
                ]);
            }
        }
    }
}
