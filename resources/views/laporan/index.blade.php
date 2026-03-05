@extends('layouts.dashboard')

@section('title', 'Laporan')
@section('subtitle', 'Lihat dan unduh laporan transaksi')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200">
                Filter
            </button>

            <a href="{{ route('laporan.index') }}" class="px-6 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition duration-200">
                Reset
            </a>
        </form>
    </div>


    <!-- Tabel Laporan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Total</th>
                        <th class="px-4 py-2 border">Bayar</th>
                        <th class="px-4 py-2 border">Kembalian</th>
                        <th class="px-4 py-2 border">Detail</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($transaksis as $trx)
                    <tr class="border-t">
                        <td class="px-4 py-2 border">
                            {{ $trx->created_at->format('d-m-Y H:i') }}
                        </td>

                        <td class="px-4 py-2 border">
                            Rp{{ number_format($trx->total,0,',','.') }}
                        </td>

                        <td class="px-4 py-2 border">
                            Rp{{ number_format($trx->bayar,0,',','.') }}
                        </td>

                        <td class="px-4 py-2 border">
                            Rp{{ number_format($trx->kembalian,0,',','.') }}
                        </td>

                        <td class="px-4 py-2 border">
                            <ul class="list-disc ml-4">
                                @foreach($trx->details as $d)
                                    <li>
                                        {{ $d->barang->nama ?? '-' }}
                                        [{{ $d->barang->kategori->nama ?? '-' }}]
                                        ({{ $d->qty }} x Rp{{ number_format($d->harga_saat_transaksi,0,',','.') }})
                                        = Rp{{ number_format($d->subtotal,0,',','.') }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>

                        <td class="px-4 py-2 border">
                            <a href="{{ route('kasir.resi', $trx->id) }}" 
                               target="_blank"
                               class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-sm transition duration-200">
                                Cetak Resi
                            </a>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            Belum ada transaksi
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transaksis->withQueryString()->links() }}
        </div>

    </div>
</div>
@endsection