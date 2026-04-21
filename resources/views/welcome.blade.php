<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php use App\Models\Setting; @endphp
    <title>{{ Setting::get('desa_nama', 'Cipakat Hub') }} - Sistem Informasi BUMDes Terpadu</title>
    <meta name="description" content="Platform digital untuk layanan administrasi desa dan pengembangan unit usaha BUMDes">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    @vite('resources/css/app.css')
    @if(Setting::get('favicon'))
        <link rel="icon" href="{{ Storage::url(Setting::get('favicon')) }}" type="image/x-icon">
    @endif
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        .gradient-bg { background: linear-gradient(135deg, #0f2b3d 0%, #1a4a6f 50%, #2c6e9e 100%); }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); }
        .banner { position: relative; height: 500px; background-size: cover; background-position: center; }
        .banner-caption { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .banner-caption h1 { font-size: 48px; font-weight: bold; margin-bottom: 16px; }
        .banner-caption p { font-size: 18px; margin-bottom: 24px; }
        .btn-slider { background: white; color: #1e3a5f; padding: 12px 30px; border-radius: 30px; font-weight: bold; transition: all 0.3s; display: inline-block; }
        .btn-slider:hover { background: #1e3a5f; color: white; transform: scale(1.05); }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        @media (max-width: 768px) { .banner { height: 350px; } .banner-caption h1 { font-size: 28px; } .banner-caption p { font-size: 14px; } }
    </style>
</head>
<body class="bg-white">

<!-- ==================== NAVBAR ==================== -->
<nav class="bg-white shadow-md border-b border-gray-100 py-4 px-6 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-2">
            @if(Setting::get('logo'))
                <img src="{{ Storage::url(Setting::get('logo')) }}" class="h-10 w-auto rounded-lg">
            @else
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-xl">CH</span>
                </div>
            @endif
            <h1 class="font-bold text-2xl text-gray-800">{{ Setting::get('desa_nama', 'Cipakat') }} <span class="text-blue-600">Hub</span></h1>
        </div>
        <div class="hidden md:flex space-x-8">
            <a href="#beranda" class="text-gray-600 hover:text-blue-600 transition font-medium">Beranda</a>
            <a href="#layanan" class="text-gray-600 hover:text-blue-600 transition font-medium">Layanan</a>
            <a href="#usaha" class="text-gray-600 hover:text-blue-600 transition font-medium">Unit Usaha</a>
            <a href="#produk" class="text-gray-600 hover:text-blue-600 transition font-medium">Produk UMKM</a>
            <a href="#tentang" class="text-gray-600 hover:text-blue-600 transition font-medium">Tentang</a>
            <a href="#kontak" class="text-gray-600 hover:text-blue-600 transition font-medium">Kontak</a>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('login') }}" class="border border-blue-600 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition font-medium">Masuk</a>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Daftar</a>
        </div>
    </div>
</nav>

<!-- ==================== HERO SECTION ==================== -->
<section id="beranda">
    <div id="hero-slider" class="slick-slider">
        <div class="banner" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?w=1200');">
            <div class="banner-caption">
                <h1>{{ Setting::get('desa_nama', 'Cipakat') }} Hub</h1>
                <p>Integrasi Layanan Administrasi Digital dan Pengembangan Unit Usaha BUMDes</p>
                <a href="{{ route('register') }}" class="btn-slider">Daftar Sekarang</a>
            </div>
        </div>
        <div class="banner" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1542838132-92c53300491e?w=1200');">
            <div class="banner-caption">
                <h1>Booking Fasilitas Desa</h1>
                <p>Booking lapangan, aula, dan peralatan desa secara online</p>
                <a href="{{ route('register') }}" class="btn-slider">Mulai Booking</a>
            </div>
        </div>
        <div class="banner" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=1200');">
            <div class="banner-caption">
                <h1>Produk UMKM Desa</h1>
                <p>Dukung produk unggulan desa dengan berbelanja di katalog UMKM</p>
                <a href="{{ route('produk.index') }}" class="btn-slider">Lihat Produk</a>
            </div>
        </div>
    </div>
</section>

<!-- ==================== STATISTIK ==================== -->
<section class="py-12 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div><div class="text-3xl md:text-4xl font-bold text-blue-600">10+</div><div class="text-gray-600 mt-1">Desa Mitra</div></div>
            <div><div class="text-3xl md:text-4xl font-bold text-blue-600">1.000+</div><div class="text-gray-600 mt-1">Layanan Selesai</div></div>
            <div><div class="text-3xl md:text-4xl font-bold text-blue-600">50+</div><div class="text-gray-600 mt-1">UMKM Terdaftar</div></div>
            <div><div class="text-3xl md:text-4xl font-bold text-blue-600">100%</div><div class="text-gray-600 mt-1">Kepuasan</div></div>
        </div>
    </div>
</section>

<!-- ==================== PRODUK UMKM TERBARU ==================== -->
<section id="produk" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Produk Unggulan</span>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">Produk UMKM Desa</h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-4">Produk unggulan dari UMKM {{ Setting::get('desa_nama', 'Desa Cipakat') }} yang siap mendukung ekonomi desa.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $produkTerbaru = App\Models\ProdukUmkm::where('status', 'aktif')->latest()->take(4)->get();
            @endphp

            @forelse($produkTerbaru as $item)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden product-card">
                <div class="h-48 bg-gray-100 overflow-hidden">
                    @if($item->gambar)
                        <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->nama_produk }}" class="w-full h-full object-cover transition duration-300 hover:scale-105">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <div class="text-xs text-blue-600 font-semibold mb-1">{{ $item->kategori }}</div>
                    <h3 class="font-bold text-gray-800 mb-1">{{ $item->nama_produk }}</h3>
                    <div class="text-lg font-bold text-blue-600">{{ $item->harga_formatted }}</div>
                    <a href="{{ route('produk.show', $item->id) }}" class="mt-3 block text-center bg-blue-50 text-blue-600 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 hover:text-white transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-gray-500">Belum ada produk UMKM</p>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('produk.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition">
                Lihat Semua Produk
            </a>
        </div>
    </div>
</section>

<!-- ==================== PENGUMUMAN DESA ==================== -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                Informasi Terkini
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">Pengumuman Desa</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Dapatkan informasi dan pengumuman terbaru dari {{ Setting::get('desa_nama', 'Desa Cipakat') }}</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($pengumuman ?? [] as $item)
            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">
                @if($item->gambar)
                <div class="h-48 overflow-hidden">
                    <img src="{{ Storage::url($item->gambar) }}" alt="{{ $item->judul }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </div>
                @else
                <div class="h-32 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-center">
                    <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                @endif

                <div class="p-5">
                    <div class="flex items-center gap-2 mb-3">
                        @if($item->jenis == 'penting')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Penting
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Informasi</span>
                        @endif
                        <span class="text-xs text-gray-400">
                            {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1 group-hover:text-blue-600 transition">
                        {{ $item->judul }}
                    </h3>

                    <p class="text-gray-500 text-sm line-clamp-2 mb-4">
                        {{ Str::limit(strip_tags($item->isi), 100) }}
                    </p>

                    <a href="{{ route('pengumuman.show', $item->id) }}"
                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-medium transition group-hover:gap-2">
                        Baca Selengkapnya
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <p class="text-gray-500">Belum ada pengumuman</p>
            </div>
            @endforelse
        </div>

        @if(isset($pengumuman) && $pengumuman->count() > 0)
        <div class="text-center mt-12">
            <a href="{{ route('pengumuman.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 hover:border-blue-300 transition-all duration-300">
                Lihat Semua Pengumuman
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- ==================== LAYANAN ADMINISTRASI ==================== -->
<section id="layanan" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Layanan Kami</span>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">Layanan Administrasi Digital</h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-4">Permudah warga dalam mengurus administrasi desa secara online, cepat, dan transparan.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-gray-50 rounded-2xl p-6 shadow-sm hover-lift">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pengajuan Surat</h3>
                <p class="text-gray-600">Surat domisili, surat usaha, keterangan tidak mampu, dan berbagai surat lainnya secara online.</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-6 shadow-sm hover-lift">
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Tracking Status</h3>
                <p class="text-gray-600">Pantau status pengajuan surat secara real-time.</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-6 shadow-sm hover-lift">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Informasi Desa</h3>
                <p class="text-gray-600">Akses informasi terbaru tentang kegiatan, pengumuman, dan layanan desa.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== UNIT USAHA BUMDES ==================== -->
<section id="usaha" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Unit Usaha</span>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">Pengembangan Unit Usaha BUMDes</h2>
            <p class="text-gray-600 max-w-2xl mx-auto mt-4">Dorong perekonomian desa melalui digitalisasi produk dan layanan UMKM.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-orange-100 hover-lift">
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Katalog Produk UMKM</h3>
                <p class="text-gray-600">Tampilkan dan kelola produk unggulan desa secara digital.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-teal-100 hover-lift">
                <div class="w-14 h-14 bg-teal-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Booking & Reservasi</h3>
                <p class="text-gray-600">Booking lapangan, aula, atau peralatan desa secara online.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-red-100 hover-lift">
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Keuangan</h3>
                <p class="text-gray-600">Pantau pemasukan dan pengeluaran BUMDes secara transparan.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== TENTANG KAMI ==================== -->
<section id="tentang" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Tentang Kami</span>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">Mengapa Cipakat Hub?</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center"><div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg></div><h3 class="text-xl font-bold text-gray-800 mb-2">BUMN Desa</h3><p class="text-gray-600">Badan Usaha Milik Desa yang berupaya bermanfaat bagi masyarakat.</p></div>
            <div class="text-center"><div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div><h3 class="text-xl font-bold text-gray-800 mb-2">Buka Lapangan Kerja</h3><p class="text-gray-600">Berperan penting dalam pengembangan usaha setiap unit.</p></div>
            <div class="text-center"><div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg></div><h3 class="text-xl font-bold text-gray-800 mb-2">Kreatif Tanpa Batas</h3><p class="text-gray-600">Menciptakan unit usaha berbasis teknologi modern.</p></div>
        </div>
    </div>
</section>

<!-- ==================== CTA ==================== -->
<section class="py-16 gradient-bg text-white">
    <div class="max-w-4xl mx-auto text-center px-6">
        <h2 class="text-3xl font-bold mb-4">Siap Mewujudkan Desa Mandiri?</h2>
        <p class="text-lg text-blue-100 mb-6">Bergabunglah dengan {{ Setting::get('desa_nama', 'Cipakat') }} Hub sekarang dan rasakan kemudahan layanan digital untuk desa Anda.</p>
        <a href="{{ route('register') }}" class="inline-block bg-white text-blue-700 px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition">Daftar Akun</a>
    </div>
</section>

<!-- ==================== FOOTER ==================== -->
<footer id="kontak" class="bg-gray-900 text-gray-400 pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    @if(Setting::get('logo'))
                        <img src="{{ Storage::url(Setting::get('logo')) }}" class="h-10 w-auto rounded-lg">
                    @else
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-xl">CH</span>
                        </div>
                    @endif
                    <h3 class="text-white font-bold text-xl">{{ Setting::get('desa_nama', 'Cipakat') }} <span class="text-blue-400">Hub</span></h3>
                </div>
                <p class="text-sm leading-relaxed">Sistem Informasi BUMDes Terpadu untuk mewujudkan desa mandiri berbasis teknologi.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Layanan</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Pengajuan Surat</a></li>
                    <li><a href="#" class="hover:text-white transition">Booking Fasilitas</a></li>
                    <li><a href="#" class="hover:text-white transition">Katalog UMKM</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Tentang</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white transition">Kontak</a></li>
                    <li><a href="#" class="hover:text-white transition">Bantuan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Kontak</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ Setting::get('kontak_email', 'info@cipakathub.com') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ Setting::get('kontak_telepon', '(021) 1234-5678') }}
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ Setting::get('kontak_alamat', 'Desa Cipakat') }}
                    </li>
                </ul>
                <div class="flex gap-3 mt-3">
                    @if(Setting::get('sosmed_facebook'))
                        <a href="{{ Setting::get('sosmed_facebook') }}" target="_blank" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if(Setting::get('sosmed_instagram'))
                        <a href="{{ Setting::get('sosmed_instagram') }}" target="_blank" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(Setting::get('sosmed_youtube'))
                        <a href="{{ Setting::get('sosmed_youtube') }}" target="_blank" class="text-gray-400 hover:text-white transition"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if(Setting::get('sosmed_tiktok'))
                        <a href="{{ Setting::get('sosmed_tiktok') }}" target="_blank" class="text-gray-400 hover:text-white transition"><i class="fab fa-tiktok"></i></a>
                    @endif
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-6 text-center text-sm">
            <p>&copy; {{ date('Y') }} {{ Setting::get('desa_nama', 'Cipakat') }} Hub - Sistem Informasi BUMDes Terpadu</p>
            <p class="mt-1">Integrasi Layanan Administrasi Digital & Pengembangan Unit Usaha BUMDes</p>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>
    $(document).ready(function(){
        $('#hero-slider').slick({
            dots: true, infinite: true, speed: 500, fade: true, autoplay: true, autoplaySpeed: 4000, arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>'
        });
    });
</script>
</body>
</html>
