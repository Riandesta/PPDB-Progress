<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\AdministrasiController;

// Route Publik
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Routes
    Route::resource('pendaftaran', PendaftaranController::class);
    Route::resource('jurusan', JurusanController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('post', PostController::class);
    Route::resource('panitia', PanitiaController::class);


    // Pendaftaran Routes
    Route::get('pendaftaran/export', [PendaftaranController::class, 'export'])->name('pendaftaran.export');
    Route::post('pendaftaran/{pendaftaran}/assign-kelas', [PendaftaranController::class, 'assignKelas'])->name('pendaftaran.assignKelas');

    // Administrasi
    Route::prefix('administrasi')->group(function () {
        Route::get('/pembayaran', [AdministrasiController::class, 'pembayaran'])->name('administrasi.pembayaran');
        Route::get('/laporan', [AdministrasiController::class, 'laporan'])->name('administrasi.laporan');
    });

    // Tahun Ajaran Management
    Route::prefix('tahun-ajaran')->group(function () {
        Route::get('/', [TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
        Route::post('/', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
        Route::put('/{id}', [TahunAjaranController::class, 'update'])->name('tahun-ajaran.update');
        Route::delete('/{id}', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');
    });

    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/pendaftaran', [LaporanController::class, 'pendaftaran'])->name('laporan.pendaftaran');
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
        Route::get('/kelulusan', [LaporanController::class, 'kelulusan'])->name('laporan.kelulusan');
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';