<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Kasir
Route::get('/kasir', [App\Http\Controllers\KasirController::class, 'index'])->name('kasir.index');
Route::post('/kasir/scan', [App\Http\Controllers\KasirController::class, 'scan'])->name('kasir.scan');
Route::post('/kasir/simpan', [App\Http\Controllers\KasirController::class, 'simpan'])->name('kasir.simpan');
Route::post('/kasir/update-qty', [App\Http\Controllers\KasirController::class, 'updateQty'])->name('kasir.updateQty');
Route::post('/kasir/remove-item', [App\Http\Controllers\KasirController::class, 'removeItem'])->name('kasir.removeItem');
Route::get('/kasir/resi/{id}', [App\Http\Controllers\KasirController::class, 'resi'])->name('kasir.resi');

// Barang (stok)
Route::resource('stok', App\Http\Controllers\BarangController::class);

// Kategori Barang
Route::get('/kategori/{id}/delete', [App\Http\Controllers\KategoriBarangController::class, 'delete'])->name('kategori.delete');
Route::resource('kategori', App\Http\Controllers\KategoriBarangController::class);

// Dashboard
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');

// Analisis
Route::get('/analisis', [App\Http\Controllers\AnalisisController::class, 'index'])->name('analisis.index');

// Laporan
Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/export', [App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');
