<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Auth routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Kasir (boleh kasir & admin)
Route::middleware(['auth', 'role:kasir,admin'])->group(function () {
    Route::get('/kasir', [App\Http\Controllers\KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/scan', [App\Http\Controllers\KasirController::class, 'scan'])->name('kasir.scan');
    Route::post('/kasir/simpan', [App\Http\Controllers\KasirController::class, 'simpan'])->name('kasir.simpan');
    Route::post('/kasir/update-qty', [App\Http\Controllers\KasirController::class, 'updateQty'])->name('kasir.updateQty');
    Route::post('/kasir/remove-item', [App\Http\Controllers\KasirController::class, 'removeItem'])->name('kasir.removeItem');
    Route::get('/kasir/resi/{id}', [App\Http\Controllers\KasirController::class, 'resi'])->name('kasir.resi');
    // Lihat stok read-only untuk kasir
    Route::get('/stok-view', [App\Http\Controllers\StokViewController::class, 'index'])->name('stok.view');
});

// Admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Barang (stok)
    Route::resource('stok', App\Http\Controllers\BarangController::class);

    // Kategori Barang
    Route::get('/kategori/{id}/delete', [App\Http\Controllers\KategoriBarangController::class, 'delete'])->name('kategori.delete');
    Route::resource('kategori', App\Http\Controllers\KategoriBarangController::class);

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
});

// Analisis
Route::get('/analisis', [App\Http\Controllers\AnalisisController::class, 'index'])->name('analisis.index');

// Laporan
Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/export', [App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');
