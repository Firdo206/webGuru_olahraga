<?php

use App\Http\Controllers\Web\AkunSiswaController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\JenisKelaminController;
use App\Http\Controllers\Web\JenisOlahragaController;
use App\Http\Controllers\Web\KelasController;
use App\Http\Controllers\Web\SiswaController;
use App\Http\Controllers\Web\StandarNilaiController;
use App\Http\Controllers\Web\SesiTesController;
use App\Http\Controllers\Web\HasilTesController;
use App\Http\Controllers\Web\Admin\GuruController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelas
    Route::resource('kelas', KelasController::class)
        ->parameters(['kelas' => 'kela']);

    // Siswa (data master, lintas kelas)
    Route::resource('siswa', SiswaController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Jenis Olahraga (data master)
    Route::resource('jenis-olahraga', JenisOlahragaController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    // Jenis Kelamin (read-only)
    Route::get('/jenis-kelamin', [JenisKelaminController::class, 'index'])->name('jenis-kelamin.index');

    // Akun & Password Siswa
    Route::prefix('akun-siswa')->name('akun-siswa.')->group(function () {
        Route::get('/', [AkunSiswaController::class, 'index'])->name('index');
        Route::post('/', [AkunSiswaController::class, 'store'])->name('store');
        Route::put('/{akunSiswa}', [AkunSiswaController::class, 'update'])->name('update');
    });

    // Standar Nilai
    Route::resource('standar-nilai', StandarNilaiController::class)
        ->only(['index', 'store', 'destroy']);

    // Sesi Tes
    Route::resource('sesi-tes', SesiTesController::class)
        ->parameters(['sesi-tes' => 'sesiTes'])
        ->only(['index', 'store', 'destroy']);
    Route::patch('/sesi-tes/{sesiTes}/status', [SesiTesController::class, 'updateStatus'])->name('sesi-tes.update-status');

    // Hasil Tes (riwayat)
    Route::get('/hasil-tes', [HasilTesController::class, 'index'])->name('hasil-tes.index');
    Route::get('/hasil-tes/{sesiTes}', [HasilTesController::class, 'show'])->name('hasil-tes.show');
    Route::get('/hasil-tes/{sesiTes}/export', [HasilTesController::class, 'export'])->name('hasil-tes.export');
  
  
    Route::middleware('role:superadmin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::put('/guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
    });
    });