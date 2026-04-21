@extends('layouts.public')

@section('title', 'Pengumuman Desa')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">

    <!-- Header -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            Informasi Desa
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">Pengumuman Desa</h1>
        <p class="text-gray-500 max-w-2xl mx-auto">Informasi dan pengumuman terbaru dari Desa Cipakat</p>
    </div>

    @if($pengumuman->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pengumuman as $item)
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
            @endforeach
        </div>

        <div class="mt-10">
            {{ $pengumuman->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <p class="text-gray-500">Belum ada pengumuman</p>
        </div>
    @endif
</div>
@endsection
