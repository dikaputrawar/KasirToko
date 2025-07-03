@extends('layouts.app')

@section('title', 'Kategori Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Kategori Barang</span>
                <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm">+ Tambah Kategori</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Kategori</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $kategori)
                            <tr>
                                <td>{{ $kategori->nama }}</td>
                                <td>
                                    <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center">Belum ada kategori</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $kategoris->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 