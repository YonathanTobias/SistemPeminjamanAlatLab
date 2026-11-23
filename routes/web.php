<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LabController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\Auth\RegisterController;

// Halaman Katalog Alat Lab (Publik)
Route::get('/', [LabController::class, 'indexPublik'])->name('lab.katalog');
Route::post('/lab/pinjam', [LabController::class, 'storePeminjamanPublik'])->name('lab.pinjam.store');
Route::get('/lacak', [LabController::class, 'lacakPeminjaman'])->name('lab.lacak');

// Halaman Peminjaman Ruangan (Publik)
Route::get('/ruangan', [RuanganController::class, 'indexPublik'])->name('lab.ruangan');
Route::post('/ruangan/pinjam', [RuanganController::class, 'storeBookingPublik'])->name('lab.ruangan.store');

// Auth (Register publik ditutup)
Auth::routes([
    'register' => false,
]);

// URL Rahasia Registrasi Laboran Baru (/sugar)
Route::get('/sugar', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/sugar', [RegisterController::class, 'register']);

// Route Dashboard Admin Laboratorium (Harus Login)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Redirect /admin langsung ke /admin/lab
    Route::redirect('/', '/admin/lab');

    // Halaman Utama Admin Dashboard (/admin/lab)
    Route::get('/lab', [LabController::class, 'adminPeminjaman'])->name('admin.lab.index');

    // 1. Halaman Kelola Peminjaman Alat
    Route::get('/peminjaman', [LabController::class, 'adminPeminjaman'])->name('admin.peminjaman.index');
    Route::post('/peminjaman/{id}/status', [LabController::class, 'updateStatusPeminjaman'])->name('admin.peminjaman.status');

    // 1.1 Halaman Kelola Ruangan & Peminjaman Ruangan
    Route::get('/ruangan', [RuanganController::class, 'adminRuangan'])->name('admin.ruangan.index');
    Route::post('/ruangan/store', [RuanganController::class, 'storeRuangan'])->name('admin.ruangan.store');
    Route::put('/ruangan/{id}', [RuanganController::class, 'updateRuangan'])->name('admin.ruangan.update');
    Route::delete('/ruangan/{id}', [RuanganController::class, 'destroyRuangan'])->name('admin.ruangan.destroy');

    Route::get('/peminjaman-ruangan', [RuanganController::class, 'adminPeminjamanRuangan'])->name('admin.ruangan.peminjaman');
    Route::post('/peminjaman-ruangan/{id}/status', [RuanganController::class, 'updateStatusPeminjaman'])->name('admin.ruangan.peminjaman.status');

    // 2. Halaman Kelola Alat Lab & Kategori Praktikum
    Route::get('/alat', [LabController::class, 'adminAlat'])->name('admin.alat.index');
    Route::post('/alat/store', [LabController::class, 'storeAlat'])->name('admin.alat.store');
    Route::put('/alat/{id}', [LabController::class, 'updateAlat'])->name('admin.alat.update');
    Route::delete('/alat/{id}', [LabController::class, 'destroyAlat'])->name('admin.alat.destroy');

    // 2.1 Kelola Kategori Praktikum
    Route::get('/kategori', [LabController::class, 'adminKategori'])->name('admin.kategori.index');
    Route::post('/kategori/store', [LabController::class, 'storeKategori'])->name('admin.kategori.store');
    Route::put('/kategori/{id}', [LabController::class, 'updateKategori'])->name('admin.kategori.update');
    Route::delete('/kategori/{id}', [LabController::class, 'destroyKategori'])->name('admin.kategori.destroy');

    // 3. Halaman Kelola Laporan & KIR
    Route::get('/laporan', [LabController::class, 'adminLaporan'])->name('admin.laporan.index');
    Route::get('/laporan/cetak-pdf', [LabController::class, 'cetakLaporanPdf'])->name('admin.laporan.pdf');
    Route::get('/laporan/export-csv', [LabController::class, 'exportLaporanCsv'])->name('admin.laporan.csv');
    Route::get('/laporan/kir/pdf', [LabController::class, 'cetakKirPdf'])->name('admin.laporan.kir.pdf');
    Route::get('/laporan/kir/excel', [LabController::class, 'exportAsetLabExcel'])->name('admin.laporan.kir.excel');
    Route::post('/laporan/kir/ttd', [LabController::class, 'updateTtdKir'])->name('admin.laporan.kir.ttd');

    // 4. Cetak PDF
    Route::get('/peminjaman/cetak-pdf', [LabController::class, 'cetakLaporanPdf'])->name('admin.peminjaman.pdf');

    // 5. Halaman Pengaturan Sistem & Profil Lab Prodi
    Route::get('/pengaturan', [LabController::class, 'adminPengaturan'])->name('admin.pengaturan.index');
    Route::post('/pengaturan', [LabController::class, 'updatePengaturan'])->name('admin.pengaturan.update');

    // 6. Halaman Kelola Pengguna / User Management (Admin IT)
    Route::get('/users', [LabController::class, 'adminUsers'])->name('admin.users.index');
    Route::post('/users', [LabController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/users/{id}', [LabController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [LabController::class, 'destroyUser'])->name('admin.users.destroy');

});