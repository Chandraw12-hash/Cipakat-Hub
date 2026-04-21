<?php

namespace App\Http\Controllers;

use App\Models\ProdukUmkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukUmkmController extends Controller
{
    // Tampilkan semua produk (katalog)
    public function index()
    {
        $produks = ProdukUmkm::where('status', 'aktif')
            ->latest()
            ->paginate(12);

        $kategoris = ProdukUmkm::select('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('produk.index', compact('produks', 'kategoris'));
    }

    // Admin: Manajemen produk
    public function adminIndex()
    {
        $produks = ProdukUmkm::with('user')->latest()->paginate(10);
        return view('produk.admin.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'unit_usaha' => 'required|string|max:255',
            'nomor_wa' => 'nullable|string|max:20',
            'alamat_toko' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk', 'public');
        }

        ProdukUmkm::create([
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'unit_usaha' => $request->unit_usaha,
            'nomor_wa' => $request->nomor_wa,
            'alamat_toko' => $request->alamat_toko,
            'is_active_wa' => $request->is_active_wa ?? 1,
            'is_active_web_order' => $request->is_active_web_order ?? 1,
            'gambar' => $gambarPath,
            'status' => $request->status,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('produk.admin')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk = ProdukUmkm::findOrFail($id);
        return view('produk.admin.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = ProdukUmkm::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'unit_usaha' => 'required|string|max:255',
            'nomor_wa' => 'nullable|string|max:20',
            'alamat_toko' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $data = [
            'nama_produk' => $request->nama_produk,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'unit_usaha' => $request->unit_usaha,
            'nomor_wa' => $request->nomor_wa,
            'alamat_toko' => $request->alamat_toko,
            'is_active_wa' => $request->is_active_wa ?? 1,
            'is_active_web_order' => $request->is_active_web_order ?? 1,
            'status' => $request->status
        ];

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.admin')
            ->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy($id)
    {
        $produk = ProdukUmkm::findOrFail($id);

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.admin')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function show($id)
    {
        $produk = ProdukUmkm::findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    public function filterByKategori($kategori)
    {
        $produks = ProdukUmkm::where('status', 'aktif')
            ->where('kategori', $kategori)
            ->latest()
            ->paginate(12);

        $kategoris = ProdukUmkm::select('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('produk.index', compact('produks', 'kategoris'));
    }
}
