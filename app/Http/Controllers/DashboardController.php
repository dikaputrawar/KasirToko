<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekAgo = Carbon::now()->subDays(7);

        // Statistik
        $totalPenjualan = Transaksi::whereDate('created_at', $today)->sum('total');
        $jumlahTransaksi = Transaksi::whereDate('created_at', $today)->count();

        // Barang terlaris minggu ini
        $barangTerlaris = TransaksiDetail::select('barang_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('transaksi', function($q) use ($weekAgo) {
                $q->where('created_at', '>=', $weekAgo);
            })
            ->groupBy('barang_id')
            ->orderByDesc('total_qty')
            ->with('barang')
            ->limit(5)
            ->get();

        // Data grafik penjualan 7 hari terakhir
        $grafik = Transaksi::select(DB::raw('DATE(created_at) as tanggal'), DB::raw('SUM(total) as total'))
            ->where('created_at', '>=', $weekAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        // Siapkan data untuk grafik (isi 0 jika tidak ada transaksi)
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayName = Carbon::now()->subDays($i)->locale('id')->format('D');
            $labels[] = $dayName;
            $total = $grafik->firstWhere('tanggal', $date);
            $data[] = $total ? (int)$total->total : 0;
        }

        // Grafik Penjualan Bulanan (REAL DATA - 12 bulan lengkap)
        $raw = DB::table('transaksis')
            ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total','bulan')
            ->toArray();

        // Siapkan array 12 bulan (isi 0 jika tidak ada data)
        $bulanData = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanData[] = isset($raw[$i]) ? (int)$raw[$i] : 0;
        }

        $bulanData = array_values($bulanData); // pastikan index 0-11

        return view('dashboard.index', compact(
            'totalPenjualan',
            'jumlahTransaksi',
            'barangTerlaris',
            'grafik',
            'labels',
            'data',
            'bulanData'
        ));
    }
}
