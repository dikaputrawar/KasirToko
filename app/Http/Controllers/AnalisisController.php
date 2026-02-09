<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalisisController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->input('end', Carbon::now()->endOfMonth()->toDateString());

        // Grafik harian
        $harian = Transaksi::select(DB::raw('DATE(created_at) as tanggal'), DB::raw('SUM(total) as total'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();
        // Grafik bulanan (1 tahun terakhir)
        $bulanan = Transaksi::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'), DB::raw('SUM(total) as total'))
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy('bulan')
            ->get();
        // Barang terlaris pada rentang tanggal
        $barangTerlaris = TransaksiDetail::select('barang_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('transaksi', function($q) use ($start, $end) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
            })
            ->groupBy('barang_id')
            ->orderByDesc('total_qty')
            ->with('barang')
            ->limit(10)
            ->get();
        return view('analisis.index', compact('harian', 'bulanan', 'barangTerlaris', 'start', 'end'));
    }
}
