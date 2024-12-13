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

// Pengumuman bisa diakses tanpa login
Route::get('/pengumuman', [PengumumanController::class, 'index'])
    ->name('pengumuman.index');

// Group route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pendaftaran PPDB
    Route::prefix('pendaftaran')->group(function () {
        Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::get('/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
        Route::post('/', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
        Route::get('/{pendaftaran}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
        Route::put('/{pendaftaran}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
        Route::delete('/{pendaftaran}', [PendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');
        Route::get('/{pendaftaran}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');

        // Route::resource('pendaftaran', PendaftaranController::class);
        Route::get('pendaftaran/export', [PendaftaranController::class, 'export'])->name('pendaftaran.export');


        // Tambahan untuk assign kelas
        Route::post('/{pendaftaran}/assign-kelas', [PendaftaranController::class, 'assignKelas'])
            ->name('pendaftaran.assignKelas');
    });
    

    // Administrasi dan Pembayaran
    Route::prefix('administrasi')->group(function () {
        // CRUD Administrasi
        Route::get('/', [AdministrasiController::class, 'index'])->name('administrasi.index');
        Route::get('/create', [AdministrasiController::class, 'create'])->name('administrasi.create');
        Route::post('/', [AdministrasiController::class, 'store'])->name('administrasi.store');
        Route::get('/{id}/edit', [AdministrasiController::class, 'edit'])->name('administrasi.edit');
        Route::put('/{id}', [AdministrasiController::class, 'update'])->name('administrasi.update');

        // Pembayaran dan Laporan
        Route::get('/pembayaran', [AdministrasiController::class, 'pembayaran'])->name('administrasi.pembayaran.index');
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

    // Manajemen Jurusan
    Route::prefix('jurusan')->group(function () {
        Route::get('/', [JurusanController::class, 'index'])->name('jurusan.index');
        Route::post('/', [JurusanController::class, 'store'])->name('jurusan.store');
        Route::put('/{jurusan}', [JurusanController::class, 'update'])->name('jurusan.update');
        Route::delete('/{jurusan}', [JurusanController::class, 'destroy'])->name('jurusan.destroy');
    });

    // Manajemen Kelas
    Route::resource('kelas', KelasController::class);
    Route::get('/kelas/distribusi', [KelasController::class, 'distribusi'])->name('kelas.distribusi');
    Route::post('/kelas/proses-distribusi', [KelasController::class, 'prosesDistribusi'])
        ->name('kelas.prosesDistribusi');

    // Manajemen Panitia
    Route::prefix('panitia')->group(function () {
        Route::get('/', [PanitiaController::class, 'index'])->name('panitia.index');
        Route::get('/create', [PanitiaController::class, 'create'])->name('panitia.create');
        Route::post('/', [PanitiaController::class, 'store'])->name('panitia.store');
        Route::get('/{id}', [PanitiaController::class, 'show'])->name('panitia.show');
        Route::get('/{id}/edit', [PanitiaController::class, 'edit'])->name('panitia.edit');
        Route::put('/{id}', [PanitiaController::class, 'update'])->name('panitia.update');
        Route::delete('/{id}', [PanitiaController::class, 'destroy'])->name('panitia.destroy');
    });

    // Manajemen Post/Pengumuman
    Route::resource('post', PostController::class);

    // Profile Management
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
