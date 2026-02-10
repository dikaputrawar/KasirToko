@extends('layouts.app')

@section('title', 'Lihat Stok Barang')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Lihat Stok Barang</h1>
</div>

<!-- Search & Filter -->
<form method="GET" action="{{ route('stok.view') }}" class="row g-3 mb-3">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode barang..." value="{{ request('search') }}">
    </div>
    <div class="col-md-4">
        <select name="kategori_id" class="form-select">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $id => $nama)
                <option value="{{ $id }}" {{ request('kategori_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="{{ route('stok.view') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

@if ($barangs->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $barang)
                    <tr>
                        <td>{{ $barang->kode }}</td>
                        <td>{{ $barang->nama }}</td>
                        <td>{{ $barang->kategori->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $barang->stok > 10 ? 'bg-success' : ($barang->stok > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $barang->stok }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $barangs->links() }}
@else
    <div class="alert alert-info">Tidak ada data barang.</div>
@endif
@endsection
