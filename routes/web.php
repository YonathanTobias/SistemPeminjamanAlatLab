<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LabController;
use App\Http\Controllers\Auth\RegisterController;

// Halaman Katalog Alat Lab (Publik)
Route::get('/', [LabController::class, 'indexPublik'])->name('lab.katalog');
Route::post('/lab/pinjam', [LabController::class, 'storePeminjamanPublik'])->name('lab.pinjam.store');

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

    // 1. Halaman Kelola Peminjaman
    Route::get('/peminjaman', [LabController::class, 'adminPeminjaman'])->name('admin.peminjaman.index');
    Route::post('/peminjaman/{id}/status', [LabController::class, 'updateStatusPeminjaman'])->name('admin.peminjaman.status');

    // 2. Halaman Kelola Alat Lab
    Route::get('/alat', [LabController::class, 'adminAlat'])->name('admin.alat.index');
    Route::post('/alat/store', [LabController::class, 'storeAlat'])->name('admin.alat.store');
    Route::delete('/alat/{id}', [LabController::class, 'destroyAlat'])->name('admin.alat.destroy');

    // 3. Halaman Kelola Laporan
    Route::get('/laporan', [LabController::class, 'adminLaporan'])->name('admin.laporan.index');
    Route::get('/laporan/cetak-pdf', [LabController::class, 'cetakLaporanPdf'])->name('admin.laporan.pdf');

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