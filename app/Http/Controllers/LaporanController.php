<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start', Carbon::now()->startOfMonth()->toDateString());
        $end = $request->input('end', Carbon::now()->endOfMonth()->toDateString());
        $transaksis = Transaksi::with(['details.barang.kategori'])
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('laporan.index', compact('transaksis', 'start', 'end'));
    }
}
