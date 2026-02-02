@extends('layouts.app')

@section('title', 'Edit Barang - WarungKu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Barang</div>
            <div class="card-body">
                <form method="POST" action="{{ route('stok.update', $barang->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Kode Barang</label>
                        <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $barang->kode) }}" required>
                        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $barang->nama) }}" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $barang->harga) }}" min="0" required>
                        @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $barang->stok) }}" min="0" required>
                        @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror" required>
                            <option value="">- Pilih Kategori -</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('stok.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 