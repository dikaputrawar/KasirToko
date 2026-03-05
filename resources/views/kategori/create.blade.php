@extends('layouts.dashboard')

@section('title', 'Tambah Kategori')
@section('subtitle', 'Tambahkan kategori baru untuk barang')

@section('content')
<div class="max-w-xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-4 text-sm text-gray-500">
        <a href="/dashboard" class="hover:text-gray-700 transition-colors">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="/kategori" class="hover:text-gray-700 transition-colors">Kategori</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Tambah</span>
    </nav>

    <!-- Card Container Modern -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all duration-200">
        <!-- Header dengan Icon -->
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Kategori</h2>
                <p class="text-sm text-gray-500">Tambahkan kategori baru untuk barang</p>
            </div>
        </div>

        <!-- Form Modern -->
        <form method="POST" action="{{ route('kategori.store') }}" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" 
                       name="nama" 
                       value="{{ old('nama') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('nama') border-red-500 @enderror" 
                       required>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-between gap-3 pt-4">
                <a href="{{ route('kategori.index') }}" 
                   class="px-5 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection