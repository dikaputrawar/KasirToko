@extends('layouts.dashboard')

@section('title', 'Analisis')
@section('subtitle', 'Analisis penjualan dan performa')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Section Grafik (Grid 2 Kolom) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Grafik 1 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Penjualan 7 Hari Terakhir</h3>
            <div class="h-[300px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Grafik 2 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Penjualan Bulanan</h3>
            <div class="h-[300px]">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Section Tabel Barang Terlaris -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Barang Terlaris (7 Hari Terakhir)</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Nama Barang</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-600">Qty Terjual</th>
                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($barangTerlaris as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-3 px-4 text-sm text-gray-800">{{ $item->nama }}</td>
                            <td class="py-3 px-4 text-sm text-gray-800 text-center">{{ $item->total_qty }}</td>
                            <td class="py-3 px-4 text-sm text-gray-800 text-right">Rp{{ number_format($item->harga,0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span>Belum ada data penjualan</span>
                                </div>
                            </td>
                        </tr>
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
document.addEventListener('DOMContentLoaded', function () {
    // Grafik 7 Hari - Sama seperti Dashboard
    const salesCanvas = document.getElementById('salesChart');
    if (salesCanvas) {
        const salesCtx = salesCanvas.getContext('2d');
        
        // Data dari controller (sama struktur Dashboard)
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

    // Grafik Bulanan
    const monthlyCanvas = document.getElementById('monthlyChart');
    if (monthlyCanvas) {
        const monthlyCtx = monthlyCanvas.getContext('2d');
        
        const monthlyData = @json($bulanData ?? []);
        console.log('Monthly Data:', monthlyData);
        
        const monthNames = [
            "Jan","Feb","Mar","Apr","Mei","Jun",
            "Jul","Agu","Sep","Okt","Nov","Des"
        ];

        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Penjualan Bulanan',
                    data: monthlyData,
                    backgroundColor: 'rgb(34, 197, 94)',
                    borderColor: 'rgb(22, 163, 74)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
});
</script>
@endpush