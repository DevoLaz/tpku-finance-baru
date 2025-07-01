<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsetTetapController;
use App\Http\Controllers\BebanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk autentikasi
require __DIR__.'/auth.php';

// Semua rute di bawah ini memerlukan login
Route::middleware(['auth', 'verified'])->group(function () {

    // --- RUTE DASAR ---
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // --- MODUL INPUT DATA ---
    Route::resource('pengadaan', PengadaanController::class);
    Route::resource('aset-tetap', AsetTetapController::class);
    Route::resource('transaksi', TransactionController::class)->except(['edit', 'update']);
    Route::resource('beban', BebanController::class)->except(['show', 'edit', 'update']);
    Route::resource('karyawan', KaryawanController::class);

    // --- MODUL LAPORAN ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'arusKas'])->name('index');
        Route::get('/arus-kas', [LaporanController::class, 'arusKas'])->name('arus_kas');
        Route::get('/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laba_rugi');
        Route::get('/neraca', [LaporanController::class, 'neraca'])->name('neraca');
        Route::get('/analisis-penjualan', [LaporanController::class, 'analisisPenjualan'])->name('penjualan');
        
        // Rute untuk Penggajian ada di dalam grup laporan
        // URL akan menjadi: /laporan/penggajian, /laporan/penggajian/create, dll.
        Route::resource('penggajian', GajiController::class)->except(['show']);
        
        // Rute untuk Slip Gaji dibuat spesifik agar tidak bentrok
        Route::get('penggajian/{gaji}', [GajiController::class, 'show'])->name('slip_gaji');
    });
});