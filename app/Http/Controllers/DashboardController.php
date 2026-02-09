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

        // Total penjualan hari ini
        $totalPenjualan = Transaksi::whereDate('created_at', $today)->sum('total');
        // Jumlah transaksi hari ini
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
        return view('dashboard.index', compact('totalPenjualan', 'jumlahTransaksi', 'barangTerlaris', 'grafik'));
    }
}
