@extends('layouts.dashboard')

@section('title', 'Edit Kategori')
@section('subtitle', 'Perbarui data kategori')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-4 text-sm text-gray-500">
        <a href="/dashboard" class="hover:text-gray-700 transition-colors">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="/kategori" class="hover:text-gray-700 transition-colors">Kategori</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Edit</span>
    </nav>

    <!-- Card Form Modern -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 transition-all duration-200 hover:shadow-md">
        <!-- Header dengan Icon -->
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Kategori</h2>
                <p class="text-sm text-gray-500">Perbarui data kategori</p>
            </div>
        </div>

        <!-- Form Modern -->
        <form method="POST" action="{{ route('kategori.update', $kategori->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" 
                       name="nama" 
                       value="{{ old('nama', $kategori->nama) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors @error('nama') border-red-500 @enderror" 
                       required>
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('kategori.index') }}" 
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection