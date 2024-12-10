<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalonSiswaController;
use App\Http\Controllers\KelasController;

// Halaman yang bisa diakses sebelum login
Route::get('/', function () {
    return view('welcome');
});

// Group route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::prefix('pendaftaran')->group(function () {
            Route::controller(CalonSiswaController::class)->group(function () {
                Route::get('/', 'show')->name('calonSiswa.show');
                Route::get('/create', 'create')->name('calonSiswa.create');
                Route::post('/', 'store')->name('calonSiswa.store');
                Route::get('/{id}/edit', 'edit')->name('calonSiswa.edit');
                Route::put('/{id}', 'update')->name('calonSiswa.update');
                Route::delete('/{id}', 'destroy')->name('calonSiswa.destroy');
            });
        });
    });

    Route::prefix('jurusan')->group(function () {
        Route::controller(JurusanController::class)->group(function () {
            Route::get('/', 'index')->name('jurusan.index');        // Menampilkan daftar jurusan
            Route::post('/', 'store')->name('jurusan.store');       // Menyimpan jurusan baru
            Route::put('/{jurusan}', 'update')->name('jurusan.update'); // Memperbarui jurusan
            Route::delete('/{jurusan}', 'destroy')->name('jurusan.destroy'); // Menghapus jurusan
        });
    });


    Route::resource('kelas', KelasController::class);






    // Route Panitia
    Route::prefix('panitia')->group(function () {
        Route::get('index', [PanitiaController::class, 'index'])->name('panitia.index');
        Route::get('create', [PanitiaController::class, 'create'])->name('panitia.create');
        Route::post('store', [PanitiaController::class, 'store'])->name('panitia.store');
        Route::get('{id}', [PanitiaController::class, 'show'])->name('panitia.show');
        Route::get('edit/{id}', [PanitiaController::class, 'edit'])->name('panitia.edit');
        Route::delete('{id}', [PanitiaController::class, 'destroy'])->name('panitia.destroy');
    });

    // Route Post
    Route::resource('post', PostController::class);
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
