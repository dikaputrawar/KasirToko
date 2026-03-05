@extends('layouts.dashboard')

@section('title', 'Stok')
@section('subtitle', 'Kelola data barang dan stok')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Card Container Utama -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Header Card -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Stok</h2>
                <p class="text-sm text-gray-500">Kelola data barang dan stok</p>
            </div>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('stok.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        + Tambah Barang
                    </a>
                @endif
            @endauth
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Kode</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border text-right">Harga</th>
                        <th class="px-4 py-2 border text-center">Stok</th>
                        <th class="px-4 py-2 border">Kategori</th>
                        <th class="px-4 py-2 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-t">
                    @forelse($barangs as $barang)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2 border text-sm text-gray-900">{{ $barang->kode }}</td>
                            <td class="px-4 py-2 border text-sm text-gray-900">{{ $barang->nama }}</td>
                            <td class="px-4 py-2 border text-sm text-gray-900 text-right font-medium">Rp{{ number_format($barang->harga,0,',','.') }}</td>
                            <td class="px-4 py-2 border text-sm text-center {{ $barang->stok <= 5 ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                                {{ $barang->stok }}
                            </td>
                            <td class="px-4 py-2 border text-sm text-gray-900">{{ $barang->kategori->nama ?? '-' }}</td>
                            <td class="px-4 py-2 border text-sm text-center">
                                @auth
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('stok.edit', $barang->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">Edit</a>
                                        <form action="{{ route('stok.destroy', $barang->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin mau hapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium ml-3 transition-colors">Hapus</button>
                                        </form>
                                    @endif
                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-400">
                                Belum ada data barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination dengan Info Dinamis -->
        <div class="flex items-center justify-between mt-4">
            <div class="text-sm text-gray-600">
                Showing {{ $barangs->firstItem() }} to {{ $barangs->lastItem() }} of {{ $barangs->total() }} results
            </div>

            <div>
                {{ $barangs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection