@extends('layouts.dashboard')

@section('title', 'Edit Barang')
@section('subtitle', 'Perbarui data produk dalam sistem')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-4 text-sm text-gray-500">
        <a href="/dashboard" class="hover:text-gray-700 transition-colors">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="/stok" class="hover:text-gray-700 transition-colors">Stok</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Edit</span>
    </nav>

    <!-- Card Form Modern -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 transition-all duration-200 hover:shadow-md">
        <!-- Header dengan Icon -->
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Barang</h2>
                <p class="text-sm text-gray-500">Perbarui data produk dalam sistem</p>
            </div>
        </div>

        <!-- Form Modern -->
        <form method="POST" action="{{ route('stok.update', $barang->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Kode Barang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                <input type="text" 
                       name="kode" 
                       value="{{ old('kode', $barang->kode) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('kode') border-red-500 @enderror" 
                       required>
                @error('kode')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Barang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" 
                       name="nama" 
                       value="{{ old('nama', $barang->nama) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('nama') border-red-500 @enderror" 
                       required>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid untuk Harga & Stok -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Harga -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" 
                               name="harga" 
                               value="{{ old('harga', $barang->harga) }}" 
                               min="0" 
                               step="1"
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('harga') border-red-500 @enderror" 
                               required>
                    </div>
                    @error('harga')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                    <input type="number" 
                           name="stok" 
                           value="{{ old('stok', $barang->stok) }}" 
                           min="0" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('stok') border-red-500 @enderror" 
                           required>
                    @error('stok')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kategori Dropdown -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="kategori_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('kategori_id') border-red-500 @enderror" 
                        required>
                    <option value="">- Pilih Kategori -</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('stok.index') }}" 
                   class="px-5 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection