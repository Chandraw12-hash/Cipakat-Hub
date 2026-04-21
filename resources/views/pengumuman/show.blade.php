@extends('layouts.public')

@section('title', $pengumuman->judul)

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Gambar Header -->
        @if($pengumuman->gambar)
        <div class="w-full max-h-96 overflow-hidden bg-gray-100">
            <img src="{{ Storage::url($pengumuman->gambar) }}"
                 alt="{{ $pengumuman->judul }}"
                 class="w-full h-full object-contain max-h-96">
        </div>
        @endif

        <div class="p-6 md:p-8">
            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($pengumuman->jenis == 'penting')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Penting
                    </span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Informasi</span>
                @endif

                @if($pengumuman->target == 'internal')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                        Internal
                    </span>
                @endif

                <span class="text-sm text-gray-400">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $pengumuman->published_at ? $pengumuman->published_at->format('d F Y H:i') : '-' }}
                </span>

                <span class="text-sm text-gray-400">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $pengumuman->user->name ?? 'Admin' }}
                </span>
            </div>

            <!-- Judul -->
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">{{ $pengumuman->judul }}</h1>

            <!-- Isi Konten -->
            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($pengumuman->isi)) !!}
            </div>

            <!-- Tombol Kembali -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('pengumuman.index') }}"
                   class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Pengumuman
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
