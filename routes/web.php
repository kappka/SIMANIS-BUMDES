<?php

use App\Http\Controllers\Admin\BarangMasukController;
use App\Http\Controllers\Admin\BiayaOperasionalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KomoditasController;
use App\Http\Controllers\Admin\LahanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\PenyesuaianStokController;
use App\Http\Controllers\Admin\PetaniController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Petani\PetaniPortalController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ============================================================
// 1. ZONA PUBLIK (Tanpa Login)
// ============================================================
Route::get('/', [PublicController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ============================================================
// 2. ZONA PETANI (Middleware: auth, role:petani)
// ============================================================
Route::middleware(['auth', 'role:petani'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [PetaniPortalController::class, 'index'])->name('dashboard');
    Route::get('/nota/{barangMasuk}/pdf', [PetaniPortalController::class, 'downloadNota'])->name('nota.pdf');
});

// ============================================================
// 3. ZONA ADMIN BUMDES (Middleware: auth, role:admin)
// ============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stok, Harga & Stock Opname
    Route::resource('komoditas', KomoditasController::class)
        ->except(['show', 'create', 'edit'])
        ->parameters(['komoditas' => 'komoditas']);
    Route::post('penyesuaian-stok', [PenyesuaianStokController::class, 'store'])->name('penyesuaian-stok.store');

    // Petani & Lahan
    Route::resource('petani', PetaniController::class)->except(['show']);
    Route::post('petani/{petani}/reset-akun', [PetaniController::class, 'resetAkun'])->name('petani.reset-akun');
    Route::resource('lahan', LahanController::class)->except(['show']);

    // Barang Masuk & Nota PDF
    Route::get('barang-masuk', [BarangMasukController::class, 'index'])->name('barang-masuk.index');
    Route::post('barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
    Route::patch('barang-masuk/{barangMasuk}/lunas', [BarangMasukController::class, 'tandaiLunas'])->name('barang-masuk.lunas');
    Route::get('barang-masuk/{barangMasuk}/pdf', [BarangMasukController::class, 'cetakNota'])->name('barang-masuk.pdf');

    // Penjualan & Nota PDF
    Route::get('penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::post('penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::patch('penjualan/{penjualan}/lunas', [PenjualanController::class, 'tandaiLunas'])->name('penjualan.lunas');
    Route::get('penjualan/{penjualan}/pdf', [PenjualanController::class, 'cetakNota'])->name('penjualan.pdf');

    // Biaya Operasional, Laporan LPJ & Pengaturan
    Route::resource('biaya-operasional', BiayaOperasionalController::class)->only(['index', 'store', 'destroy']);
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
});
