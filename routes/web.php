<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Auth routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Kasir & Admin (bisa lihat stok dan kategori)
Route::middleware(['auth', 'role:kasir,admin'])->group(function () {
    Route::get('/kasir', [App\Http\Controllers\KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/scan', [App\Http\Controllers\KasirController::class, 'scan'])->name('kasir.scan');
    Route::post('/kasir/simpan', [App\Http\Controllers\KasirController::class, 'simpan'])->name('kasir.simpan');
    Route::post('/kasir/update-qty', [App\Http\Controllers\KasirController::class, 'updateQty'])->name('kasir.updateQty');
    Route::post('/kasir/remove-item', [App\Http\Controllers\KasirController::class, 'removeItem'])->name('kasir.removeItem');
    Route::get('/kasir/resi/{id}', [App\Http\Controllers\KasirController::class, 'resi'])->name('kasir.resi');
    Route::get('/kasir/resi/{id}/pdf', [App\Http\Controllers\KasirController::class, 'resiPdf'])->name('kasir.resi.pdf');
    
    // Lihat stok (read-only untuk kasir, full untuk admin)
    Route::get('/stok', [App\Http\Controllers\BarangController::class, 'index'])->name('stok.index');
    Route::get('/kategori', [App\Http\Controllers\KategoriBarangController::class, 'index'])->name('kategori.index');
});

// Admin only (full CRUD)
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Barang (stok) - full CRUD
    Route::get('/stok/create', [App\Http\Controllers\BarangController::class, 'create'])->name('stok.create');
    Route::post('/stok', [App\Http\Controllers\BarangController::class, 'store'])->name('stok.store');
    Route::get('/stok/{id}/edit', [App\Http\Controllers\BarangController::class, 'edit'])->name('stok.edit');
    Route::put('/stok/{id}', [App\Http\Controllers\BarangController::class, 'update'])->name('stok.update');
    Route::delete('/stok/{id}', [App\Http\Controllers\BarangController::class, 'destroy'])->name('stok.destroy');

    // Kategori Barang - full CRUD
    Route::get('/kategori/create', [App\Http\Controllers\KategoriBarangController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [App\Http\Controllers\KategoriBarangController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [App\Http\Controllers\KategoriBarangController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [App\Http\Controllers\KategoriBarangController::class, 'update'])->name('kategori.update');
    Route::get('/kategori/{id}/delete', [App\Http\Controllers\KategoriBarangController::class, 'delete'])->name('kategori.delete');
    Route::delete('/kategori/{id}', [App\Http\Controllers\KategoriBarangController::class, 'destroy'])->name('kategori.destroy');

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
});

// Analisis
Route::get('/analisis', [App\Http\Controllers\AnalisisController::class, 'index'])->name('analisis.index');

// Laporan
Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/export', [App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');
