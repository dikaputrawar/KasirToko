@extends('layouts.app')

@section('title', 'Data Barang - WarungKu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Data Barang</span>
                <a href="{{ route('stok.create') }}" class="btn btn-primary btn-sm">+ Tambah Barang</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Kategori</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangs as $barang)
                            <tr>
                                <td>{{ $barang->kode }}</td>
                                <td>{{ $barang->nama }}</td>
                                <td>Rp{{ number_format($barang->harga,0,',','.') }}</td>
                                <td>{{ $barang->stok }}</td>
                                <td>{{ $barang->kategori->nama ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('stok.edit', $barang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('stok.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center">Belum ada data barang</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $barangs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 