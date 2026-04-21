@extends('layouts.app')

@section('title', 'Order Produk')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h1 class="text-lg font-semibold text-gray-800">Form Pemesanan Produk</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi form di bawah untuk memesan produk</p>
        </div>

        <form action="{{ route('produk.order.store', $produk->id) }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                <input type="text" value="{{ $produk->nama_produk }}" readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                <input type="text" value="{{ $produk->harga_formatted }}" readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Usaha / Penjual</label>
                <input type="text" value="{{ $produk->unit_usaha }}" readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" id="jumlah" value="1" min="1" max="{{ $produk->stok }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <p class="text-xs text-gray-500 mt-1">Stok tersedia: {{ $produk->stok }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                    Kirim Pesanan
                </button>
                <a href="{{ route('produk.show', $produk->id) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
