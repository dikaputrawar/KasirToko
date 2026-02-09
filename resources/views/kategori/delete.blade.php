@extends('layouts.app')

@section('title', 'Hapus Kategori - WarungKu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Konfirmasi Hapus Kategori</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="alert alert-warning">
                    <h6>Kategori: <strong>{{ $kategori->nama }}</strong></h6>
                    @if($productCount > 0)
                        <p class="mb-0">Kategori ini memiliki <strong>{{ $productCount }}</strong> produk terkait.</p>
                        @if($totalTransactions > 0)
                            <p class="mb-0">Produk-produk ini memiliki <strong>{{ $totalTransactions }}</strong> detail transaksi terkait.</p>
                        @endif
                    @else
                        <p class="mb-0">Kategori ini tidak memiliki produk terkait.</p>
                    @endif
                </div>

                @if($productCount > 0)
                    <!-- Detailed breakdown of affected products -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Produk yang akan terpengaruh:</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama Produk</th>
                                            <th>Stok</th>
                                            <th>Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product)
                                        <tr>
                                            <td>{{ $product->kode }}</td>
                                            <td>{{ $product->nama }}</td>
                                            <td>{{ $product->stok }}</td>
                                            <td>{{ $product->transaksiDetails->count() }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6>Pilih tindakan:</h6>
                        
                        <!-- Option 1: Soft Delete -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-warning">
                                    <i class="fas fa-archive"></i> Arsipkan Kategori (Soft Delete)
                                </h6>
                                <p class="card-text text-muted">
                                    Kategori dan produk akan ditandai sebagai dihapus tetapi data tetap tersimpan.
                                    Transaksi tetap aman dan dapat diakses.
                                </p>
                                <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="action" value="soft_delete">
                                    <button type="submit" class="btn btn-warning">
                                        Arsipkan
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Option 2: Cascade Delete -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-danger">
                                    <i class="fas fa-trash"></i> Hapus Permanen Kategori dan Semua Produk
                                </h6>
                                <p class="card-text text-muted">
                                    Semua produk dalam kategori ini akan dihapus secara permanen.
                                    @if($totalTransactions > 0)
                                        <br><strong>Peringatan:</strong> {{ $totalTransactions }} detail transaksi juga akan dihapus!
                                    @endif
                                </p>
                                <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="action" value="cascade">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('PERHATIAN! Semua produk dalam kategori ini akan dihapus secara permanen. Lanjutkan?')">
                                        Hapus Permanen
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Option 2: Reassign Products -->
                        @if($otherKategoris->count() > 0)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">
                                        <i class="fas fa-exchange-alt"></i> Pindahkan Produk ke Kategori Lain
                                    </h6>
                                    <p class="card-text text-muted">
                                        Pindahkan semua produk ke kategori lain sebelum menghapus kategori ini.
                                    </p>
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="action" value="reassign">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="new_kategori_id" class="form-select" required>
                                                    <option value="">Pilih kategori tujuan</option>
                                                    @foreach($otherKategoris as $otherKategori)
                                                        <option value="{{ $otherKategori->id }}">{{ $otherKategori->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-warning">
                                                    Pindahkan & Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Simple delete for categories without products -->
                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus kategori ini?')">
                            Hapus Kategori
                        </button>
                    </form>
                @endif

                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </div>
</div>
@endsection 