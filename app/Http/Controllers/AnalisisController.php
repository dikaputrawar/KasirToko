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

        // Data grafik penjualan 7 hari terakhir (sama seperti Dashboard)
        $grafik = DB::table('transaksis')
            ->selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupByRaw('DATE(created_at)')
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

        // Konversi ke collection untuk compatibility dengan Dashboard
        $grafik = collect([
            ['tanggal' => $labels[0], 'total' => $data[0]],
            ['tanggal' => $labels[1], 'total' => $data[1]],
            ['tanggal' => $labels[2], 'total' => $data[2]],
            ['tanggal' => $labels[3], 'total' => $data[3]],
            ['tanggal' => $labels[4], 'total' => $data[4]],
            ['tanggal' => $labels[5], 'total' => $data[5]],
            ['tanggal' => $labels[6], 'total' => $data[6]],
        ]);

        // PENJUALAN BULANAN (TAHUN INI)
        
        $raw = DB::table('transaksis')
            ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total','bulan')
            ->toArray();

        for ($i = 1; $i <= 12; $i++) {
            $bulanData[] = isset($raw[$i]) ? (int)$raw[$i] : 0;
        }

        $bulanData = array_values($bulanData);

        // BARANG TERLARIS 7 HARI TERAKHIR
        
        $barangTerlaris = DB::table('transaksi_details')
            ->join('transaksis', 'transaksi_details.transaksi_id', '=', 'transaksis.id')
            ->join('barangs', 'transaksi_details.barang_id', '=', 'barangs.id')
            ->where('transaksis.created_at', '>=', Carbon::now()->subDays(7))
            ->select(
                'barangs.nama',
                'barangs.harga',
                DB::raw('SUM(transaksi_details.qty) as total_qty')
            )
            ->groupBy('barangs.nama', 'barangs.harga')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return view('analisis.index', compact('grafik', 'bulanData', 'barangTerlaris', 'start', 'end'));
    }
}
