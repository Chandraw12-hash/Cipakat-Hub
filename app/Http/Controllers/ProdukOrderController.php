<?php

namespace App\Http\Controllers;

use App\Models\ProdukUmkm;
use App\Models\OrderProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukOrderController extends Controller
{
    public function create($id)
    {
        $produk = ProdukUmkm::findOrFail($id);
        return view('produk.order', compact('produk'));
    }

    public function store(Request $request, $id)
    {
        $produk = ProdukUmkm::findOrFail($id);

        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $produk->stok,
            'keterangan' => 'nullable|string'
        ]);

        $order = OrderProduk::create([
            'produk_id' => $produk->id,
            'user_id' => Auth::id(),
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'total_harga' => $produk->harga * $request->jumlah,
            'status' => 'pending'
        ]);

        return redirect()->route('produk.order.history')->with('success', 'Pesanan berhasil dikirim. Silakan tunggu konfirmasi dari admin.');
    }

    public function history()
    {
        $orders = OrderProduk::with('produk')->where('user_id', Auth::id())->latest()->paginate(10);
        return view('produk.order_history', compact('orders'));
    }
}
