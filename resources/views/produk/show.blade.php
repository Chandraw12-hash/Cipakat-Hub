@extends('layouts.public')
@section('title', $produk->nama_produk)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm">
        <a href="{{ route('produk.index') }}" class="text-gray-500 hover:text-blue-600 transition">Katalog Produk</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gray-700 font-medium">{{ $produk->nama_produk }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="grid md:grid-cols-2 gap-0">
            <!-- Kiri: Gallery Gambar -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-8 flex items-center justify-center">
                <div class="w-full">
                    @if($produk->gambar)
                        <img src="{{ Storage::url($produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                             class="w-full max-h-96 object-contain rounded-xl shadow-lg transition-transform duration-500 hover:scale-105">
                    @else
                        <div class="w-full h-96 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center rounded-xl">
                            <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kanan: Info Produk -->
            <div class="p-8 lg:p-10">
                <!-- Badge -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-block px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-full text-xs font-semibold shadow-sm">
                        {{ $produk->kategori }}
                    </span>
                    @if($produk->stok > 0)
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            Tersedia
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                            Habis
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-3">{{ $produk->nama_produk }}</h1>

                <div class="text-4xl lg:text-5xl font-bold text-blue-600 mb-6">
                    {{ $produk->harga_formatted }}
                </div>

                <!-- Info Ringkas -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <svg class="w-5 h-5 text-gray-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                        <p class="text-xs text-gray-500">Stok</p>
                        <p class="font-semibold text-gray-800">{{ $produk->stok }} unit</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <svg class="w-5 h-5 text-gray-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <p class="text-xs text-gray-500">Penjual</p>
                        <p class="font-semibold text-gray-800">{{ $produk->unit_usaha }}</p>
                    </div>
                </div>

                <!-- Alamat Toko -->
                @if($produk->alamat_toko)
                <div class="flex items-start gap-3 mb-6 p-4 bg-blue-50 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-blue-600">Lokasi Toko</p>
                        <p class="text-sm text-gray-700">{{ $produk->alamat_toko }}</p>
                    </div>
                </div>
                @endif

                <!-- Deskripsi -->
                <div class="mb-8">
                    <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Deskripsi Produk
                    </h3>
                    <p class="text-gray-600 leading-relaxed">{{ $produk->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                </div>

                <!-- Tombol Aksi Premium -->
                <div class="flex flex-col sm:flex-row gap-4 mt-6">
                    @if($produk->is_active_wa == 1 && $produk->nomor_wa)
                        @php
                            $message = urlencode("Halo, saya tertarik dengan produk {$produk->nama_produk}. Apakah masih tersedia?");
                            $waLink = "https://wa.me/{$produk->nomor_wa}?text={$message}";
                        @endphp
                        <a href="{{ $waLink }}" target="_blank"
                           class="flex-1 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.032 2.032c-5.524 0-10 4.476-10 10 0 1.752.45 3.42 1.28 4.876L2.2 21.8l4.892-1.112c1.456.83 3.124 1.28 4.876 1.28 5.524 0 10-4.476 10-10s-4.476-10-10-10z"/>
                            </svg>
                            Beli via WhatsApp
                        </a>
                    @endif

                    @if($produk->is_active_web_order == 1)
                        <a href="{{ route('produk.order', $produk->id) }}"
                           class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M17 13l1.5 6M9 21h6M12 18v3"/>
                            </svg>
                            Order via Website
                        </a>
                    @endif
                </div>

                <!-- Tombol Kembali -->
                <div class="mt-8 text-center">
                    <a href="{{ route('produk.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 transition duration-300 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Katalog
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Produk Lain -->
    @php
        $rekomendasi = App\Models\ProdukUmkm::where('kategori', $produk->kategori)
            ->where('id', '!=', $produk->id)
            ->where('status', 'aktif')
            ->limit(4)
            ->get();
    @endphp

    @if($rekomendasi->count() > 0)
    <div class="mt-16">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Produk Rekomendasi</h2>
            <a href="{{ route('produk.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($rekomendasi as $item)
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="h-40 bg-gray-100 overflow-hidden">
                    @if($item->gambar)
                        <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <div class="text-xs text-blue-600 font-semibold mb-1">{{ $item->kategori }}</div>
                    <h3 class="font-bold text-gray-800 mb-1 line-clamp-1">{{ $item->nama_produk }}</h3>
                    <div class="text-lg font-bold text-blue-600">{{ $item->harga_formatted }}</div>
                    <a href="{{ route('produk.show', $item->id) }}" class="mt-3 block text-center bg-gray-100 text-gray-700 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 hover:text-white transition">Lihat Detail</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
