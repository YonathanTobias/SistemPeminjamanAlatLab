<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\PeminjamanRuangan;
use App\Models\PengaturanLab;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RuanganController extends Controller
{
    // Halaman Publik Daftar Ruangan & Form Booking
    public function indexPublik(Request $request)
    {
        $ruangans = Ruangan::where('status', '!=', 'Tidak Aktif')->get();
        $pengaturan = PengaturanLab::first();

        return view('lab.ruangan', compact('ruangans', 'pengaturan'));
    }

    // Submit Booking Ruangan oleh Pengguna Publik
    public function storeBookingPublik(Request $request)
    {
        // 0. Deteksi Dini Payload SQL Injection & Scanner Keamanan Otomatis
        $suspectFields = [$request->nama_peminjam, $request->nim_nip, $request->prodi, $request->keperluan, $request->catatan];
        $sqlInjectionPattern = '/(\b(select|sleep|pg_sleep|benchmark|dbms_pipe|waitfor|delay|union\s+select|information_schema|load_file|into\s+outfile|chr\s*\()\b|--|\/\*|\*\/|@@)/i';

        foreach ($suspectFields as $val) {
            if ($val && preg_match($sqlInjectionPattern, $val)) {
                return back()->with('error', 'Karakter atau kata kunci tidak valid terdeteksi pada isian formulir. Pengajuan ditolak demi keamanan sistem!')->withInput();
            }
        }

        $request->validate([
            'ruangan_id'    => 'required|exists:ruangans,id',
            'nama_peminjam' => ['required', 'string', 'max:150', 'regex:/^[a-zA-Z\s.,\'\-]+$/u'],
            'nim_nip'       => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\/\.\-\s]+$/'],
            'prodi'         => 'required|string|max:100',
            'keperluan'     => 'required|string|max:255',
            'tgl_pinjam'    => 'required|date|after_or_equal:today',
            'jam_mulai'     => 'required',
            'jam_selesai'   => 'required|after:jam_mulai',
            'catatan'       => 'nullable|string|max:500',
        ], [
            'nama_peminjam.regex' => 'Nama peminjam hanya boleh berisi huruf dan karakter nama yang valid (tidak boleh mengandung simbol aneh atau script).',
            'nim_nip.regex'       => 'NIM/NIP hanya boleh berisi huruf, angka, garis miring, atau tanda hubung.',
        ]);

        // Cek bentrok jadwal ruangan yang sudah Disetujui atau masih Menunggu
        $bentrok = PeminjamanRuangan::where('ruangan_id', $request->ruangan_id)
            ->where('tgl_pinjam', $request->tgl_pinjam)
            ->whereIn('status', ['Menunggu', 'Disetujui'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                          ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            })
            ->exists();

        if ($bentrok) {
            return back()->with('error', 'Maaf, ruangan pada tanggal dan jam tersebut sudah diajukan/digunakan peminjam lain. Silakan pilih waktu lain.')->withInput();
        }

        $kodeTransaksi = 'RNG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        PeminjamanRuangan::create([
            'kode_transaksi' => $kodeTransaksi,
            'ruangan_id' => $request->ruangan_id,
            'nama_peminjam' => $request->nama_peminjam,
            'nim_nip' => $request->nim_nip,
            'prodi' => $request->prodi,
            'keperluan' => $request->keperluan,
            'tgl_pinjam' => $request->tgl_pinjam,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => 'Menunggu',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('lab.lacak', ['q' => $kodeTransaksi, 'tab' => 'ruangan'])
            ->with('success', 'Pengajuan peminjaman ruangan berhasil dikirim dengan Kode: ' . $kodeTransaksi . '. Anda dapat langsung melacak statusnya di sini.');
    }

    // Admin: Kelola Master Data Ruangan
    public function adminRuangan()
    {
        $ruangans = Ruangan::withCount('peminjamans')->latest()->paginate(10);
        $pengaturan = PengaturanLab::first();

        return view('admin.ruangan.index', compact('ruangans', 'pengaturan'));
    }

    public function storeRuangan(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|string|unique:ruangans,kode_ruangan|max:50',
            'nama_ruangan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:0',
            'fasilitas' => 'nullable|string',
            'status' => 'required|in:Tersedia,Perbaikan,Tidak Aktif',
        ]);

        Ruangan::create($request->all());

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil ditambahkan.');
    }

    public function updateRuangan(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'kode_ruangan' => 'required|string|max:50|unique:ruangans,kode_ruangan,' . $id,
            'nama_ruangan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:0',
            'fasilitas' => 'nullable|string',
            'status' => 'required|in:Tersedia,Perbaikan,Tidak Aktif',
        ]);

        $ruangan->update($request->all());

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroyRuangan($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('admin.ruangan.index')->with('success', 'Data ruangan berhasil dihapus.');
    }

    // Admin: Kelola Booking Ruangan & Update Status
    public function adminPeminjamanRuangan(Request $request)
    {
        $query = PeminjamanRuangan::with('ruangan');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_peminjam', 'like', '%' . $request->search . '%')
                  ->orWhere('nim_nip', 'like', '%' . $request->search . '%');
            });
        }

        $peminjamans = $query->latest()->paginate(15)->withQueryString();
        $pengaturan = PengaturanLab::first();

        return view('admin.ruangan.peminjaman', compact('peminjamans', 'pengaturan'));
    }

    public function updateStatusPeminjaman(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Disetujui,Ditolak,Selesai',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman = PeminjamanRuangan::findOrFail($id);
        $peminjaman->update([
            'status' => $request->status,
            'catatan' => $request->catatan ?? $peminjaman->catatan,
        ]);

        return back()->with('success', 'Status peminjaman ruangan berhasil diperbarui menjadi: ' . $request->status);
    }
}
