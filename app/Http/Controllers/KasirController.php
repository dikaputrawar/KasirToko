<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    // Tampilkan halaman kasir
    public function index(Request $request)
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(function($item) {
            return $item['qty'] * $item['harga'];
        });
        return view('kasir.index', compact('cart', 'total'));
    }

    // Scan/input kode barang (AJAX)
    public function scan(Request $request)
    {
        $request->validate(['kode' => 'required']);
        $barang = Barang::where('kode', $request->kode)->first();
        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }
        if ($barang->stok < 1) {
            return response()->json(['error' => 'Stok habis'], 422);
        }
        $cart = session('cart', []);
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $barang->id) {
                if ($item['qty'] + 1 > $barang->stok) {
                    return response()->json(['error' => 'Stok tidak cukup'], 422);
                }
                $item['qty']++;
                $found = true;
                break;
            }
        }
        unset($item);
        if (!$found) {
            $cart[] = [
                'id' => $barang->id,
                'kode' => $barang->kode,
                'nama' => $barang->nama,
                'harga' => $barang->harga,
                'qty' => 1,
                'stok' => $barang->stok,
            ];
        }
        session(['cart' => $cart]);
        if ($request->ajax()) {
            $total = collect($cart)->sum(fn($item) => $item['qty'] * $item['harga']);
            return response()->json([
                'html' => view('kasir._cart', ['cart' => $cart, 'total' => $total])->render()
            ]);
        }
        return response()->json(['cart' => $cart]);
    }

    // Update qty item (AJAX)
    public function updateQty(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'qty' => 'required|integer|min:1',
        ]);
        $cart = session('cart', []);
        foreach ($cart as &$item) {
            if ($item['id'] == $request->id) {
                $barang = Barang::find($item['id']);
                if ($request->qty > $barang->stok) {
                    return response()->json(['error' => 'Stok tidak cukup'], 422);
                }
                $item['qty'] = $request->qty;
                break;
            }
        }
        unset($item);
        session(['cart' => $cart]);
        if ($request->ajax()) {
            $total = collect($cart)->sum(fn($item) => $item['qty'] * $item['harga']);
            return response()->json([
                'html' => view('kasir._cart', ['cart' => $cart, 'total' => $total])->render()
            ]);
        }
        return response()->json(['cart' => $cart]);
    }

    // Hapus item dari cart (AJAX)
    public function removeItem(Request $request)
    {
        $request->validate(['id' => 'required']);
        $cart = session('cart', []);
        $cart = array_filter($cart, function($item) use ($request) {
            return $item['id'] != $request->id;
        });
        session(['cart' => array_values($cart)]);
        $cart = array_values($cart);
        if ($request->ajax()) {
            $total = collect($cart)->sum(fn($item) => $item['qty'] * $item['harga']);
            return response()->json([
                'html' => view('kasir._cart', ['cart' => $cart, 'total' => $total])->render()
            ]);
        }
        return response()->json(['cart' => $cart]);
    }

    // Simpan transaksi
    public function simpan(Request $request)
    {
        $request->validate([
            'bayar' => 'required|integer|min:0',
        ]);
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart kosong!');
        }
        $total = collect($cart)->sum(function($item) {
            return $item['qty'] * $item['harga'];
        });
        if ($request->bayar < $total) {
            return back()->with('error', 'Uang bayar kurang!');
        }
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'total' => $total,
                'bayar' => $request->bayar,
                'kembalian' => $request->bayar - $total,
                'user_id' => auth()->id(),
            ]);
            foreach ($cart as $item) {
                $barang = Barang::find($item['id']);
                if ($barang->stok < $item['qty']) {
                    throw new \Exception('Stok tidak cukup untuk ' . $barang->nama);
                }
                $barang->stok -= $item['qty'];
                $barang->save();
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $barang->id,
                    'qty' => $item['qty'],
                    'harga_saat_transaksi' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga'],
                ]);
            }
            DB::commit();
            session()->forget('cart');
            return redirect()->route('kasir.resi', $transaksi->id)->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    // Tampilkan resi transaksi
    public function resi($id)
    {
        $transaksi = Transaksi::with(['details.barang', 'user'])->findOrFail($id);
        return view('kasir.resi', compact('transaksi'));
    }
}
