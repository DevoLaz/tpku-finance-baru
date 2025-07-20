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

// Rute untuk autentikasi
require __DIR__.'/auth.php';


Route::get('/transaksi/fetch-from-api', [TransactionController::class, 'fetchFromApi'])->name('transaksi.fetchApi');
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
    Route::get('pengadaan/export-pdf', [PengadaanController::class, 'exportPdf'])->name('pengadaan.exportPdf');
    Route::resource('pengadaan', PengadaanController::class);
    
    Route::resource('aset-tetap', AsetTetapController::class);
    
    Route::get('transaksi/export-pdf', [TransactionController::class, 'exportPdf'])->name('transaksi.exportPdf');
    Route::resource('transaksi', TransactionController::class)->except(['edit', 'update']);
    
    Route::get('beban/export-pdf', [BebanController::class, 'exportPdf'])->name('beban.exportPdf');
    Route::resource('beban', BebanController::class)->except(['show', 'edit', 'update']);

    Route::resource('karyawan', KaryawanController::class);

    // --- MODUL LAPORAN ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'arusKas'])->name('index');
        
        Route::get('/arus-kas', [LaporanController::class, 'arusKas'])->name('arus_kas');
        Route::get('/arus-kas/export-pdf', [LaporanController::class, 'exportArusKasPdf'])->name('arus_kas.exportPdf');
        
        Route::get('/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laba_rugi');
        Route::get('/laba-rugi/export-pdf', [LaporanController::class, 'exportLabaRugiPdf'])->name('laba_rugi.exportPdf');

        Route::get('/neraca', [LaporanController::class, 'neraca'])->name('neraca');
        Route::get('/neraca/export-pdf', [LaporanController::class, 'exportNeracaPdf'])->name('neraca.exportPdf'); // <-- SENTUHAN AKHIR DI SINI

        Route::get('/analisis-penjualan', [LaporanController::class, 'analisisPenjualan'])->name('penjualan');
        
        Route::resource('penggajian', GajiController::class)->except(['show']);
        Route::get('penggajian/{gaji}', [GajiController::class, 'show'])->name('slip_gaji');
    });
});
