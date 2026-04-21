@extends('layouts.app')

@section('title', 'Detail Pengajuan Surat')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">Detail Pengajuan Surat</h1>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Jenis Surat</p>
                    <p class="font-medium">{{ $surat->jenis_surat }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
                    <p class="font-medium">{{ $surat->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p>{!! $surat->status_badge !!}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Keterangan</p>
                    <p class="font-medium">{{ $surat->keterangan ?? '-' }}</p>
                </div>
                @if($surat->file_pendukung)
                <div>
                    <p class="text-sm text-gray-500">File Pendukung</p>
                    <a href="{{ Storage::url($surat->file_pendukung) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a>
                </div>
                @endif
                @if($surat->catatan_admin)
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Catatan Admin</p>
                    <p class="font-medium text-red-600">{{ $surat->catatan_admin }}</p>
                </div>
                @endif
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('layanan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Kembali
                </a>
                <a href="{{ route('layanan.tracking', $surat->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Tracking Status
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
