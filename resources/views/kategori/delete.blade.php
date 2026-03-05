@extends('layouts.dashboard')

@section('title', 'Hapus Kategori')
@section('subtitle', 'Konfirmasi penghapusan kategori')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-4 text-sm text-gray-500">
        <a href="/dashboard" class="hover:text-gray-700 transition-colors">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="/kategori" class="hover:text-gray-700 transition-colors">Kategori</a>
        <span class="mx-2">/</span>
        <span class="text-gray-700">Hapus</span>
    </nav>

    <!-- Card Konfirmasi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <!-- Header -->
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Konfirmasi Hapus Kategori</h2>
                <p class="text-sm text-gray-500">Periksa data sebelum menghapus</p>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Info Kategori -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.932-3L13.932 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.932 3z"></path>
                </svg>
                <div>
                    <h6 class="font-semibold text-yellow-800">Kategori: <strong>{{ $kategori->nama }}</strong></h6>
                    @if($productCount > 0)
                        <p class="text-sm text-yellow-700">Kategori ini memiliki <strong>{{ $productCount }}</strong> produk terkait.</p>
                        @if($totalTransactions > 0)
                            <p class="text-sm text-yellow-700">Produk-produk ini memiliki <strong>{{ $totalTransactions }}</strong> detail transaksi terkait.</p>
                        @endif
                    @else
                        <p class="text-sm text-yellow-700">Kategori ini tidak memiliki produk terkait.</p>
                    @endif
                </div>
            </div>
        </div>

        @if($productCount > 0)
            <!-- Detail Produk Terkait -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h6 class="font-semibold text-gray-800 mb-3">Produk yang akan terpengaruh:</h6>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left py-2 px-3 font-medium text-gray-700">Kode</th>
                                <th class="text-left py-2 px-3 font-medium text-gray-700">Nama Produk</th>
                                <th class="text-center py-2 px-3 font-medium text-gray-700">Stok</th>
                                <th class="text-center py-2 px-3 font-medium text-gray-700">Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                            <tr>
                                <td class="py-2 px-3">{{ $product->kode }}</td>
                                <td class="py-2 px-3">{{ $product->nama }}</td>
                                <td class="py-2 px-3 text-center">{{ $product->stok }}</td>
                                <td class="py-2 px-3 text-center">{{ $product->transaksiDetails->count() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Opsi Tindakan -->
            <div class="space-y-4">
                <h6 class="font-semibold text-gray-800">Pilih tindakan:</h6>
                
                <!-- Opsi 1: Soft Delete -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <h6 class="font-semibold text-yellow-800">Arsipkan Kategori (Soft Delete)</h6>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Kategori dan produk akan ditandai sebagai dihapus tetapi data tetap tersimpan. Transaksi tetap aman dan dapat diakses.</p>
                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="action" value="soft_delete">
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Arsipkan
                        </button>
                    </form>
                </div>

                <!-- Opsi 2: Cascade Delete -->
                <div class="border border-red-200 rounded-lg p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <h6 class="font-semibold text-red-800">Hapus Permanen Kategori dan Semua Produk</h6>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">
                        Semua produk dalam kategori ini akan dihapus secara permanen.
                        @if($totalTransactions > 0)
                            <br><strong>Peringatan:</strong> {{ $totalTransactions }} detail transaksi juga akan dihapus!
                        @endif
                    </p>
                    <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="inline" onsubmit="return confirm('PERHATIAN! Semua produk dalam kategori ini akan dihapus secara permanen. Lanjutkan?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="action" value="cascade">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Hapus Permanen
                        </button>
                    </form>
                </div>

                <!-- Opsi 3: Reassign Products -->
                @if($otherKategoris->count() > 0)
                    <div class="border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4a2 2 0 01-2-2V7a2 2 0 012-2h4"></path>
                                </svg>
                            </div>
                            <h6 class="font-semibold text-blue-800">Pindahkan Produk ke Kategori Lain</h6>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">Pindahkan semua produk ke kategori lain sebelum menghapus kategori ini.</p>
                        <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="action" value="reassign">
                            <div class="flex items-center gap-3">
                                <select name="new_kategori_id" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Pilih kategori tujuan</option>
                                    @foreach($otherKategoris as $otherKategori)
                                        <option value="{{ $otherKategori->id }}">{{ $otherKategori->nama }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Pindahkan & Hapus
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @else
            <!-- Simple Delete untuk kategori tanpa produk -->
            <div class="text-center py-6">
                <p class="text-gray-600 mb-4">Kategori ini tidak memiliki produk terkait. Aman untuk dihapus.</p>
                <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Hapus Kategori
                    </button>
                </form>
            </div>
        @endif

        <!-- Tombol Batal -->
        <div class="mt-6 text-center">
            <a href="{{ route('kategori.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                Batal
            </a>
        </div>
    </div>
</div>
@endsection