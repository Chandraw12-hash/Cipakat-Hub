@extends('layouts.app')

@section('title', 'Katalog Produk UMKM')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Katalog Produk UMKM</h1>
        <p class="text-gray-500 mt-1">Produk unggulan dari desa kami</p>
    </div>

    <!-- Filter Kategori -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('produk.index') }}"
           class="px-4 py-2 rounded-lg text-sm font-medium {{ !request()->segment(2) ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
            Semua
        </a>
        @foreach($kategoris as $kat)
            <a href="{{ route('produk.kategori', $kat) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->segment(3) == $kat ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                {{ $kat }}
            </a>
        @endforeach
    </div>

    <!-- Grid Produk -->
    @if($produks->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($produks as $item)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition group">
                <!-- Gambar -->
                <div class="h-48 bg-gray-100 overflow-hidden">
                    @if($item->gambar)
                        <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->nama_produk }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="p-4">
                    <div class="text-xs text-blue-600 font-semibold mb-1">{{ $item->kategori }}</div>
                    <h3 class="font-bold text-gray-800 mb-1 line-clamp-1">{{ $item->nama_produk }}</h3>
                    <p class="text-sm text-gray-500 mb-2 line-clamp-2">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    <div class="text-lg font-bold text-blue-600">{{ $item->harga_formatted }}</div>
                    <div class="text-xs text-gray-500 mt-1">Stok: {{ $item->stok }} | {{ $item->unit_usaha }}</div>

                    <a href="{{ route('produk.show', $item->id) }}"
                       class="mt-3 block text-center bg-blue-50 text-blue-600 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 hover:text-white transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $produks->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-500">Belum ada produk UMKM</p>
        </div>
    @endif
</div>
@endsection
