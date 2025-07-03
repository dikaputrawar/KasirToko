@extends('layouts.app')

@section('title', 'Analisis Penjualan')

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
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Grafik Penjualan Harian</div>
            <div class="card-body">
                <canvas id="grafikHarian" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">Grafik Penjualan Bulanan (1 Tahun)</div>
            <div class="card-body">
                <canvas id="grafikBulanan" height="120"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">Barang Terlaris ({{ $start }} s/d {{ $end }})</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Qty Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangTerlaris as $item)
                    <tr>
                        <td>{{ $item->barang->nama ?? '-' }}</td>
                        <td>{{ $item->total_qty }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik Harian
const ctxHarian = document.getElementById('grafikHarian').getContext('2d');
new Chart(ctxHarian, {
    type: 'bar',
    data: {
        labels: {!! json_encode($harian->pluck('tanggal')->map(fn($t)=>date('d M',strtotime($t)))) !!},
        datasets: [{
            label: 'Penjualan Harian (Rp)',
            data: {!! json_encode($harian->pluck('total')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
// Grafik Bulanan
const ctxBulanan = document.getElementById('grafikBulanan').getContext('2d');
new Chart(ctxBulanan, {
    type: 'line',
    data: {
        labels: {!! json_encode($bulanan->pluck('bulan')->map(fn($b)=>date('M Y',strtotime($b.'-01')))) !!},
        datasets: [{
            label: 'Penjualan Bulanan (Rp)',
            data: {!! json_encode($bulanan->pluck('total')) !!},
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush 