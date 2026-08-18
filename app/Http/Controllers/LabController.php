<?php

namespace App\Http\Controllers;

use App\Models\AlatLab;
use App\Models\PeminjamanLab;
use App\Models\PeminjamanLabDetail;
use App\Models\PengaturanLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LabController extends Controller
{
   // 1. Method indexPublik
    public function indexPublik(Request $request)
    {
        $query = AlatLab::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_alat', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_alat', 'like', '%' . $request->search . '%');
            });
        }

        $alats = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total_alat' => AlatLab::count(),
            'total_tersedia' => AlatLab::sum('stok_tersedia'),
            'kondisi_baik' => AlatLab::where('kondisi', 'Baik')->count(),
        ];

        // Ambil semua alat yang tersedia untuk katalog keranjang
        $allAlats = AlatLab::where('stok_tersedia', '>', 0)->get(['id', 'kode_alat', 'nama_alat', 'stok_tersedia', 'kondisi']);

        return view('lab.katalog', compact('alats', 'stats', 'allAlats'));
    }

    // Form Pengajuan Pinjam Multi-Alat Publik (Keranjang Praktikum)
    public function storePeminjamanPublik(Request $request)
    {
        $request->validate([
            'nama_peminjam'       => 'required|string|max:255',
            'nim_nip'             => 'required|string|max:50',
            'prodi'               => 'required|string|max:100',
            'keperluan'           => 'nullable|string|max:255',
            'tgl_pinjam'          => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
            'items'               => 'required_without:alat_lab_id|array|min:1',
            'items.*.alat_lab_id' => 'required_with:items|exists:alat_labs,id',
            'items.*.jumlah'      => 'required_with:items|integer|min:1',
            'alat_lab_id'         => 'nullable|exists:alat_labs,id',
            'jumlah_pinjam'       => 'nullable|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            // Persiapkan array item peminjaman
            $items = [];
            if ($request->filled('items') && is_array($request->items)) {
                $items = $request->items;
            } elseif ($request->filled('alat_lab_id')) {
                $items[] = [
                    'alat_lab_id' => $request->alat_lab_id,
                    'jumlah'      => $request->jumlah_pinjam ?? 1,
                ];
            }

            if (empty($items)) {
                return back()->with('error', 'Tidak ada alat laboratorium yang dipilih untuk dipinjam!');
            }

            // 1. Validasi ketersediaan stok seluruh alat yang diminta
            foreach ($items as $item) {
                $alat = AlatLab::findOrFail($item['alat_lab_id']);
                $qty = (int) ($item['jumlah'] ?? $item['jumlah_pinjam'] ?? 1);
                if ($qty > $alat->stok_tersedia) {
                    return back()->with('error', "Stok alat '{$alat->nama_alat}' tidak mencukupi! (Tersedia: {$alat->stok_tersedia}, Diminta: {$qty})");
                }
            }

            // 2. Generate kode transaksi unik: TRX-YYYYMMDD-XXX
            $countToday = PeminjamanLab::whereDate('created_at', Carbon::today())->count() + 1;
            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

            // 3. Buat header peminjaman
            $peminjaman = PeminjamanLab::create([
                'kode_transaksi'      => $kodeTransaksi,
                'nama_peminjam'       => $request->nama_peminjam,
                'nim_nip'             => $request->nim_nip,
                'prodi'               => $request->prodi,
                'keperluan'           => $request->keperluan ?? 'Praktikum Laboratorium Keperawatan',
                'tgl_pinjam'          => $request->tgl_pinjam,
                'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
                'status'              => 'Menunggu',
            ]);

            // 4. Buat rincian detail alat yang dipinjam
            foreach ($items as $item) {
                $qty = (int) ($item['jumlah'] ?? $item['jumlah_pinjam'] ?? 1);
                PeminjamanLabDetail::create([
                    'peminjaman_lab_id' => $peminjaman->id,
                    'alat_lab_id'       => $item['alat_lab_id'],
                    'jumlah_pinjam'     => $qty,
                ]);
            }

            return back()->with('success', "Pengajuan peminjaman paket alat [{$kodeTransaksi}] berhasil dikirim! Silakan tunggu konfirmasi Laboran.");
        });
    }

    // Dashboard Admin Laboran
    public function adminIndex()
    {
        $alats = AlatLab::latest()->get();
        $peminjamans = PeminjamanLab::with('details.alat')->latest()->paginate(10);

        return view('admin.lab.index', compact('alats', 'peminjamans'));
    }

    // Admin: Tambah Alat Lab
   // Admin: Simpan Alat Baru
    public function storeAlat(Request $request)
    {
        $request->validate([
            'kode_alat'  => 'required|unique:alat_labs,kode_alat',
            'nama_alat'  => 'required|string|max:255',
            'stok_total' => 'required|integer|min:1',
        ]);

        AlatLab::create([
            'kode_alat'     => $request->kode_alat,
            'nama_alat'     => $request->nama_alat,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'kondisi'       => $request->kondisi ?? 'Baik',
        ]);

        return back()->with('success', 'Data alat lab berhasil ditambahkan!');
    }

    // Admin: Setujui / Tolak / Kembalikan Paket Alat
    public function updateStatusPeminjaman(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Disetujui,Ditolak,Dikembalikan',
            'catatan' => 'nullable|string'
        ]);

        return DB::transaction(function () use ($request, $id) {
            $peminjaman = PeminjamanLab::with('details.alat')->findOrFail($id);
            $statusLama = $peminjaman->status;
            $statusBaru = $request->status;

            if ($statusLama === $statusBaru) {
                return redirect()->back()->with('info', 'Tidak ada perubahan status.');
            }

            // 1. Status diubah menjadi Disetujui
            if ($statusBaru === 'Disetujui') {
                // Periksa stok seluruh item terlebih dahulu
                foreach ($peminjaman->details as $detail) {
                    if (!$detail->alat || $detail->alat->stok_tersedia < $detail->jumlah_pinjam) {
                        $namaAlat = $detail->alat->nama_alat ?? 'Alat';
                        $tersedia = $detail->alat->stok_tersedia ?? 0;
                        return redirect()->back()->with('error', "Stok alat {$namaAlat} tidak mencukupi! (Tersedia: {$tersedia}, Diminta: {$detail->jumlah_pinjam})");
                    }
                }
                // Kurangi stok untuk tiap item alat
                foreach ($peminjaman->details as $detail) {
                    $detail->alat->decrement('stok_tersedia', $detail->jumlah_pinjam);
                }
            }

            // 2. Status diubah menjadi Dikembalikan
            if ($statusBaru === 'Dikembalikan') {
                if ($statusLama === 'Disetujui') {
                    foreach ($peminjaman->details as $detail) {
                        if ($detail->alat) {
                            $detail->alat->increment('stok_tersedia', $detail->jumlah_pinjam);
                        }
                    }
                }
                $peminjaman->tgl_kembali_realisasi = now();
            }

            // 3. Pembatalan/Penolakan setelah sempat disetujui
            if (($statusBaru === 'Ditolak' || $statusBaru === 'Menunggu') && $statusLama === 'Disetujui') {
                foreach ($peminjaman->details as $detail) {
                    if ($detail->alat) {
                        $detail->alat->increment('stok_tersedia', $detail->jumlah_pinjam);
                    }
                }
            }

            $peminjaman->status = $statusBaru;
            if ($request->filled('catatan')) {
                $peminjaman->catatan = $request->catatan;
            }
            $peminjaman->save();

            return redirect()->back()->with('success', "Status transaksi peminjaman [{$peminjaman->kode_transaksi}] berhasil diperbarui menjadi {$statusBaru}.");
        });
    }

    // Admin: Hapus Alat
    public function destroyAlat($id)
    {
        $alat = AlatLab::findOrFail($id);
        $alat->delete();

        return back()->with('success', 'Data alat lab berhasil dihapus!');
    }
    // Admin: Halaman Kelola Peminjaman Alat
    public function adminPeminjaman(Request $request)
    {
        $query = PeminjamanLab::with('details.alat');

        // Filter Tanggal
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tgl_pinjam', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->tgl_selesai);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Pencarian Peminjam / Kode Transaksi
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_peminjam', 'like', '%' . $request->search . '%')
                  ->orWhere('nim_nip', 'like', '%' . $request->search . '%');
            });
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => PeminjamanLab::count(),
            'menunggu' => PeminjamanLab::where('status', 'Menunggu')->count(),
            'disetujui' => PeminjamanLab::where('status', 'Disetujui')->count(),
            'dikembalikan' => PeminjamanLab::where('status', 'Dikembalikan')->count(),
            'ditolak' => PeminjamanLab::where('status', 'Ditolak')->count(),
        ];

        return view('admin.peminjaman.index', compact('peminjaman', 'stats'));
    }

    // Admin: Halaman Kelola Inventaris Alat Lab (Halaman Sendiri)
    public function adminAlat(Request $request)
    {
        $query = AlatLab::query();

        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_alat', 'like', '%' . $request->search . '%');
        }

        $alats = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_jenis' => AlatLab::count(),
            'total_stok' => AlatLab::sum('stok_total') ?? 0,
            'stok_tersedia' => AlatLab::sum('stok_tersedia') ?? 0,
            'kondisi_baik' => AlatLab::where('kondisi', 'Baik')->count(),
            'kondisi_rusak' => AlatLab::where('kondisi', '!=', 'Baik')->count(),
        ];

        return view('admin.alat.index', compact('alats', 'stats'));
    }

    public function adminLaporan(Request $request)
    {
        $query = PeminjamanLab::with('details.alat');

        // Filter berdasarkan tanggal mulai
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tgl_pinjam', '>=', $request->tgl_mulai);
        }

        // Filter berdasarkan tanggal selesai
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->tgl_selesai);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil data terbaru dengan paginasi
        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => PeminjamanLab::count(),
            'menunggu' => PeminjamanLab::where('status', 'Menunggu')->count(),
            'disetujui' => PeminjamanLab::where('status', 'Disetujui')->count(),
            'dikembalikan' => PeminjamanLab::where('status', 'Dikembalikan')->count(),
        ];

        return view('admin.laporan.index', compact('peminjaman', 'stats'));
    }

    public function cetakLaporanPdf(Request $request)
    {
        $query = PeminjamanLab::with('details.alat');

        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tgl_pinjam', '>=', $request->tgl_mulai);
        }
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->tgl_selesai);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->latest()->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('peminjaman'));
        return $pdf->stream('laporan-peminjaman-lab.pdf');
    }

    // Admin: Halaman Pengaturan Sistem & Profil Lab Prodi
    public function adminPengaturan()
    {
        $pengaturan = PengaturanLab::getPengaturan();
        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    // Admin: Update Pengaturan Sistem
    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'nama_sistem'       => 'required|string|max:255',
            'nama_prodi'        => 'required|string|max:255',
            'nama_institusi'    => 'required|string|max:255',
            'unit_laboratorium' => 'required|string|max:255',
            'kepala_lab'        => 'required|string|max:255',
            'nip_kepala_lab'    => 'nullable|string|max:100',
            'alamat_institusi'  => 'required|string|max:255',
            'kontak_lab'        => 'nullable|string|max:100',
            'logo'              => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $pengaturan = PengaturanLab::getPengaturan();

        $data = [
            'nama_sistem'       => $request->nama_sistem,
            'nama_prodi'        => $request->nama_prodi,
            'nama_institusi'    => $request->nama_institusi,
            'unit_laboratorium' => $request->unit_laboratorium,
            'kepala_lab'        => $request->kepala_lab,
            'nip_kepala_lab'    => $request->nip_kepala_lab ?? '-',
            'alamat_institusi'  => $request->alamat_institusi,
            'kontak_lab'        => $request->kontak_lab,
        ];

        // Handle upload logo baru jika ada
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'logo-stikes-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            $data['logo_path'] = 'images/' . $fileName;
        }

        $pengaturan->update($data);

        return back()->with('success', 'Pengaturan nama sistem dan profil laboratorium berhasil diperbarui!');
    }
}