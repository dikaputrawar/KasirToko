@extends('layouts.app')

@section('title', 'Laporan Transaksi - WarungKu')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">Filter Tanggal</div>
            <div class="card-body">
                <form class="row g-3" method="GET" action="">
                    <div class="col-auto">
                        <label>Dari</label>
                        <input type="date" name="start" class="form-control" value="{{ $start }}">
                    </div>
                    <div class="col-auto">
                        <label>Sampai</label>
                        <input type="date" name="end" class="form-control" value="{{ $end }}">
                    </div>
                    <div class="col-auto align-self-end">
                        <button class="btn btn-primary">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">Daftar Transaksi</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Kembalian</th>
                        <th>Detail Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $trx)
                    <tr>
                        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td>Rp{{ number_format($trx->total,0,',','.') }}</td>
                        <td>Rp{{ number_format($trx->bayar,0,',','.') }}</td>
                        <td>Rp{{ number_format($trx->kembalian,0,',','.') }}</td>
                        <td>
                            <ul class="mb-0">
                                @foreach($trx->details as $d)
                                <li>{{ $d->barang->nama ?? '-' }} <span class="text-muted">[{{ $d->barang->kategori->nama ?? '-' }}]</span> ({{ $d->qty }} x Rp{{ number_format($d->harga_saat_transaksi,0,',','.') }}) = Rp{{ number_format($d->subtotal,0,',','.') }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <a href="{{ route('kasir.resi', $trx->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-print"></i> Cetak Resi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>
            {{ $transaksis->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection 