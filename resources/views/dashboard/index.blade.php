@extends('layouts.app')

@section('title', 'Dashboard - WarungKu')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Penjualan Hari Ini</h5>
                <h3>Rp{{ number_format($totalPenjualan,0,',','.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Jumlah Transaksi</h5>
                <h3>{{ $jumlahTransaksi }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Barang Terlaris (7 hari)</h5>
                <ol class="mb-0">
                    @foreach($barangTerlaris as $item)
                        <li>{{ $item->barang->nama ?? '-' }} <span class="badge bg-dark">{{ $item->total_qty }}</span></li>
                    @endforeach
                    @if($barangTerlaris->isEmpty())
                        <li><em>Belum ada data</em></li>
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">Grafik Penjualan 7 Hari Terakhir</div>
    <div class="card-body">
        <canvas id="grafikPenjualan" height="80"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikPenjualan').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($grafik->pluck('tanggal')->map(fn($t)=>date('d M',strtotime($t)))) !!},
        datasets: [{
            label: 'Penjualan (Rp)',
            data: {!! json_encode($grafik->pluck('total')) !!},
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endpush 