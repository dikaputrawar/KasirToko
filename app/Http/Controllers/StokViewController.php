<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class StokViewController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori')->orderBy('nama');
        
        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kode', 'like', '%' . $search . '%');
            });
        }
        
        // Filter kategori
        if ($kategoriId = $request->get('kategori_id')) {
            $query->where('kategori_id', $kategoriId);
        }
        
        $barangs = $query->paginate(10)->withQueryString();
        $kategoris = \App\Models\KategoriBarang::orderBy('nama')->pluck('nama', 'id');
        
        return view('stok.view', compact('barangs', 'kategoris'));
    }
}
