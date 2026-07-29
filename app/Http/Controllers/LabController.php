<?php

namespace App\Http\Controllers;

use App\Models\AlatLab;
use App\Models\PeminjamanLab;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LabController extends Controller
{
   // 1. Method indexPublik
    public function indexPublik(Request $request)
    {
        $query = AlatLab::query();

        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%')
                ->orWhere('kode_alat', 'like', '%' . $request->search . '%');
        }

        // Filter kategori dihapus
        $alats = $query->latest()->paginate(12);

        return view('lab.katalog', compact('alats'));
    }

    // Form Pengajuan Pinjam Publik
    public function storePeminjamanPublik(Request $request)
    {
        $request->validate([
            'alat_lab_id'         => 'required|exists:alat_labs,id',
            'nama_peminjam'       => 'required|string|max:255',
            'nim_nip'             => 'required|string|max:50',
            'prodi'               => 'required|string|max:100',
            'jumlah_pinjam'       => 'required|integer|min:1',
            'tgl_pinjam'          => 'required|date',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam',
        ]);

        $alat = AlatLab::findOrFail($request->alat_lab_id);

        if ($request->jumlah_pinjam > $alat->stok_tersedia) {
            return back()->with('error', 'Jumlah pinjam melebihi stok yang tersedia!');
        }

        PeminjamanLab::create([
            'alat_lab_id'         => $request->alat_lab_id,
            'nama_peminjam'       => $request->nama_peminjam,
            'nim_nip'             => $request->nim_nip,
            'prodi'               => $request->prodi,
            'jumlah_pinjam'       => $request->jumlah_pinjam,
            'tgl_pinjam'          => $request->tgl_pinjam,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'status'              => 'Menunggu',
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil dikirim! Silakan tunggu konfirmasi Laboran.');
    }

    // Dashboard Admin Laboran
    public function adminIndex()
    {
        $alats = AlatLab::latest()->get();
        $peminjamans = PeminjamanLab::with('alat')->latest()->paginate(10);

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

    // // Admin: Setujui / Tolak / Kembalikan Alat
    // public function updateStatusPeminjaman(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:Disetujui,Ditolak,Dikembalikan',
    //     ]);

    //     $peminjaman = PeminjamanLab::findOrFail($id);
    //     $alat = $peminjaman->alat;

    //     if ($request->status == 'Disetujui' && $peminjaman->status == 'Menunggu') {
    //         if ($peminjaman->jumlah_pinjam > $alat->stok_tersedia) {
    //             return back()->with('error', 'Stok alat tidak mencukupi untuk menyetujui peminjaman ini.');
    //         }
    //         $alat->decrement('stok_tersedia', $peminjaman->jumlah_pinjam);
    //     } elseif ($request->status == 'Dikembalikan' && $peminjaman->status == 'Disetujui') {
    //         $alat->increment('stok_tersedia', $peminjaman->jumlah_pinjam);
    //         $peminjaman->tgl_kembali_realisasi = now();
    //     }

    //     $peminjaman->status = $request->status;
    //     $peminjaman->save();

    //     return back()->with('success', 'Status peminjaman berhasil diperbarui!');
    // }

    public function updateStatusPeminjaman(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Disetujui,Ditolak,Dikembalikan',
            'catatan' => 'nullable|string'
        ]);

        return DB::transaction(function () use ($request, $id) {
            $peminjaman = PeminjamanLab::with('alat')->findOrFail($id);
            $statusLama = $peminjaman->status;
            $statusBaru = $request->status;

            if ($statusLama === $statusBaru) {
                return redirect()->back()->with('info', 'Tidak ada perubahan status.');
            }

            $alat = $peminjaman->alat;

            if (!$alat) {
                return redirect()->back()->with('error', 'Data alat laboratorium tidak ditemukan.');
            }

            // 1. Status diubah menjadi Disetujui
            if ($statusBaru === 'Disetujui') {
                if ($alat->stok_tersedia < $peminjaman->jumlah_pinjam) {
                    return redirect()->back()->with('error', "Stok alat {$alat->nama_alat} tidak mencukupi! (Tersedia: {$alat->stok_tersedia}, Diminta: {$peminjaman->jumlah_pinjam})");
                }
                $alat->decrement('stok_tersedia', $peminjaman->jumlah_pinjam);
            }

            // 2. Status diubah menjadi Dikembalikan
            if ($statusBaru === 'Dikembalikan') {
                if ($statusLama === 'Disetujui') {
                    $alat->increment('stok_tersedia', $peminjaman->jumlah_pinjam);
                }
                $peminjaman->tgl_kembali_realisasi = now();
            }

            // 3. Pembatalan/Penolakan setelah sempat disetujui
            if (($statusBaru === 'Ditolak' || $statusBaru === 'Menunggu') && $statusLama === 'Disetujui') {
                $alat->increment('stok_tersedia', $peminjaman->jumlah_pinjam);
            }

            $peminjaman->status = $statusBaru;
            if ($request->filled('catatan')) {
                $peminjaman->catatan = $request->catatan;
            }
            $peminjaman->save();

            return redirect()->back()->with('success', "Status peminjaman berhasil diperbarui menjadi {$statusBaru}.");
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
    $query = PeminjamanLab::with('alat');

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

    // Ambil data peminjaman terbaru dan tambahkan query string untuk paginasi
    $peminjaman = $query->latest()->paginate(10)->withQueryString();

    // PASTIKAN 'peminjaman' dimasukkan ke dalam compact()
    return view('admin.peminjaman.index', compact('peminjaman'));
}

    // Admin: Halaman Kelola Inventaris Alat Lab (Halaman Sendiri)
    public function adminAlat(Request $request)
    {
        $query = AlatLab::query();

        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_alat', 'like', '%' . $request->search . '%');
        }

        $alats = $query->latest()->paginate(10);
        return view('admin.alat.index', compact('alats'));
    }

    public function adminLaporan(Request $request)
    {
        $query = Peminjaman::query();

        // Filter berdasarkan tanggal mulai
        if ($request->filled('tgl_mulai')) {
            $query->whereDate('created_at', '>=', $request->tgl_mulai);
        }

        // Filter berdasarkan tanggal selesai
        if ($request->filled('tgl_selesai')) {
            $query->whereDate('created_at', '<=', $request->tgl_selesai);
        }

        // Filter berdasarkan status (misal: disetujui, ditolak, pending)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil data terbaru dengan paginasi
        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('admin.laporan.index', compact('peminjaman'));
    }

   public function cetakLaporanPdf(Request $request)
{
    $query = PeminjamanLab::with('alat'); // <-- Ganti menjadi PeminjamanLab

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

    
}