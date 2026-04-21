@extends('layouts.app')

@section('title', 'Pengajuan Surat Saya')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pengajuan Surat Saya</h1>
            <p class="text-gray-500 mt-1">Riwayat pengajuan surat menyurat</p>
        </div>
        <a href="{{ route('layanan.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Surat
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @if($surats->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Jenis Surat</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($surats as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-medium">{{ $item->jenis_surat }}</td>
                            <td class="px-6 py-4">{!! $item->status_badge !!}</td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 flex-wrap">
                                    <a href="{{ route('layanan.show', $item->id) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                                    <a href="{{ route('layanan.tracking', $item->id) }}" class="text-green-600 hover:text-green-800">Tracking</a>

                                    {{-- Tombol Download - hanya muncul jika status selesai dan file_surat ada --}}
                                    @if($item->status == 'selesai' && $item->file_surat)
                                        <a href="{{ route('layanan.download', $item->id) }}" class="text-purple-600 hover:text-purple-800">
                                            Download PDF
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $surats->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500">Belum ada pengajuan surat</p>
                <a href="{{ route('layanan.create') }}" class="mt-3 inline-block text-blue-600 hover:underline">Ajukan surat sekarang</a>
            </div>
        @endif
    </div>
</div>
@endsection
