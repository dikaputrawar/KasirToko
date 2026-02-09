<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBarang;

class KategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriBarang::orderBy('id', 'desc')->paginate(10);
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:kategori_barangs,nama',
        ]);
        KategoriBarang::create($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        $request->validate([
            'nama' => 'required|unique:kategori_barangs,nama,' . $kategori->id,
        ]);
        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Show the form for deleting the specified resource.
     */
    public function delete($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        $productCount = $kategori->barangs()->count();
        $otherKategoris = KategoriBarang::where('id', '!=', $id)->get();
        
        // Get detailed information about associated products and their transactions
        $products = $kategori->barangs()->with('transaksiDetails')->get();
        $totalTransactions = 0;
        foreach ($products as $product) {
            $totalTransactions += $product->transaksiDetails->count();
        }
        
        return view('kategori.delete', compact('kategori', 'productCount', 'otherKategoris', 'products', 'totalTransactions'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        $action = $request->input('action', 'check');
        
        // Check if category has associated products
        if ($kategori->barangs()->count() > 0) {
            if ($action === 'cascade') {
                // Database will handle cascade deletion automatically
                $kategori->delete();
                return redirect()->route('kategori.index')
                    ->with('success', 'Kategori dan semua produk terkait berhasil dihapus!');
            } elseif ($action === 'soft_delete') {
                // Soft delete - mark as deleted but keep data
                $kategori->delete(); // This will now use soft delete
                return redirect()->route('kategori.index')
                    ->with('success', 'Kategori berhasil dihapus (data tetap tersimpan)!');
            } elseif ($action === 'reassign' && $request->has('new_kategori_id')) {
                // Reassign products to another category
                $newKategoriId = $request->input('new_kategori_id');
                $kategori->barangs()->update(['kategori_id' => $newKategoriId]);
                $kategori->delete();
                return redirect()->route('kategori.index')
                    ->with('success', 'Kategori berhasil dihapus dan produk telah dipindahkan!');
            } else {
                return redirect()->route('kategori.delete', $id)
                    ->with('error', 'Kategori memiliki produk terkait. Silakan pilih tindakan yang sesuai.');
            }
        }
        
        try {
            $kategori->delete();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('kategori.index')
                ->with('error', 'Terjadi kesalahan saat menghapus kategori. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage (simple version).
     */
    public function destroySimple($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        
        try {
            $kategori->delete();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('kategori.index')
                ->with('error', 'Terjadi kesalahan saat menghapus kategori. Silakan coba lagi.');
        }
    }
}
