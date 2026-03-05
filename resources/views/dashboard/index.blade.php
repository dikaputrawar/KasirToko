@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di Warungku POS')

@section('content')
<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card 1: Penjualan Hari Ini -->
    <div class="bg-green-600 text-white rounded-2xl p-6 shadow-md">
        <div class="flex items-center justify-between mb-2">
            <span class="text-green-100 text-sm">Penjualan Hari Ini</span>
            <i class="fas fa-chart-line text-green-200"></i>
        </div>
        <div class="text-3xl font-bold mb-1">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        <div class="text-xs text-green-100">+12% dari kemarin</div>
    </div>

    <!-- Card 2: Jumlah Transaksi -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-cash-register text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-800">{{ $jumlahTransaksi }}</div>
        <div class="text-sm text-gray-500">Transaksi hari ini</div>
    </div>

    <!-- Card 3: Total Produk -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-box text-purple-600 text-xl"></i>
            </div>
        </div>
        <div class="text-3xl font-bold text-gray-800">{{ \App\Models\Barang::count() }}</div>
        <div class="text-sm text-gray-500">Total produk</div>
    </div>
</div>

<!-- Grafik Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Grafik Area 7 Hari -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Penjualan 7 Hari</h3>
        <div class="h-80">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Grafik Batang Bulanan -->
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Penjualan Bulanan</h3>
        <div class="h-80">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<!-- Barang Terlaris -->
<div class="bg-white rounded-2xl p-6 shadow-sm">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Barang Terlaris Minggu Ini</h3>
    <div class="space-y-3">
        @forelse($barangTerlaris as $index => $item)
            <div class="flex items-center justify-between p-3 rounded-xl {{ $index === 0 ? 'bg-orange-50' : 'bg-gray-50' }}">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-8 rounded-full {{ $index === 0 ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-sm font-semibold">
                        {{ $index + 1 }}
                    </span>
                    <span class="font-medium text-gray-800">{{ $item->barang->nama ?? '-' }}</span>
                </div>
                <span class="text-sm font-semibold text-gray-600">{{ $item->total_qty }} pcs</span>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">Belum ada data transaksi minggu ini.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Grafik 7 Hari - Safe Implementation
    const salesCanvas = document.getElementById('salesChart');
    if (salesCanvas) {
        const salesCtx = salesCanvas.getContext('2d');
        
        // Data dari controller
        const salesLabels = {!! json_encode($grafik->pluck('tanggal')->map(function($t) {
            return date('d M', strtotime($t));
        })) !!};
        const salesData = {!! json_encode($grafik->pluck('total')) !!};
        
        console.log('Sales Data:', { labels: salesLabels, data: salesData });
        
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Penjualan',
                    data: salesData,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // Grafik Bulanan - Safe Implementation
    const monthlyCanvas = document.getElementById('monthlyChart');
    if (monthlyCanvas) {
        const monthlyCtx = monthlyCanvas.getContext('2d');
        
        // Data dari controller
        const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        const monthlyData = @json($bulanData);
        
        console.log('Monthly Data:', monthlyData);
        console.log('Monthly Data Length:', monthlyData.length);
        
        // Validasi data
        if (!monthlyData || monthlyData.length !== 12) {
            console.error('Monthly data invalid:', monthlyData);
            return;
        }
        
        // Format ticks dinamis
        const maxValue = Math.max(...monthlyData);
        const useJutaan = maxValue >= 1000000;
        const hasData = monthlyData.some(value => value > 0);
        
        console.log('Max Value:', maxValue, 'Use Jutaan:', useJutaan, 'Has Data:', hasData);
        
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthNames, // Selalu 12 bulan
                datasets: [{
                    label: 'Penjualan',
                    data: monthlyData, // Array 12 angka
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderRadius: 8,
                    borderWidth: 1,
                    borderColor: 'rgba(34, 197, 94, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                return 'Penjualan: Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (hasData && useJutaan) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                } else if (hasData) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                } else {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush