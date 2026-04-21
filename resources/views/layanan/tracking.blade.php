@extends('layouts.app')

@section('title', 'Tracking Surat')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">Tracking Pengajuan Surat</h1>
            <p class="text-gray-500 text-sm mt-1">Jenis Surat: {{ $surat->jenis_surat }}</p>
        </div>

        <div class="p-6">
            <div class="relative">
                <!-- Timeline -->
                <div class="space-y-6">
                    <!-- Status Pending -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $surat->status == 'pending' ? 'bg-yellow-500 ring-4 ring-yellow-200' : ($surat->status != 'pending' ? 'bg-green-500' : 'bg-gray-300') }} flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            @if($surat->status != 'pending')
                            <div class="w-0.5 h-12 bg-green-500 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-6">
                            <h3 class="font-semibold">Pengajuan Dikirim</h3>
                            <p class="text-sm text-gray-500">{{ $surat->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-sm text-gray-600">Pengajuan surat telah diterima oleh sistem</p>
                        </div>
                    </div>

                    <!-- Status Diproses -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $surat->status == 'diproses' ? 'bg-blue-500 ring-4 ring-blue-200' : ($surat->status == 'selesai' || $surat->status == 'ditolak' ? 'bg-green-500' : 'bg-gray-300') }} flex items-center justify-center">
                                @if($surat->status == 'diproses')
                                <svg class="w-4 h-4 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                @elseif($surat->status == 'selesai' || $surat->status == 'ditolak')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                @endif
                            </div>
                            @if($surat->status == 'diproses' || $surat->status == 'selesai' || $surat->status == 'ditolak')
                            <div class="w-0.5 h-12 {{ $surat->status == 'selesai' || $surat->status == 'ditolak' ? 'bg-green-500' : 'bg-blue-500' }} mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-6">
                            <h3 class="font-semibold">Sedang Diproses</h3>
                            <p class="text-sm text-gray-500">{{ $surat->updated_at->format('d/m/Y H:i') }}</p>
                            <p class="text-sm text-gray-600">Pengajuan sedang diproses oleh petugas</p>
                        </div>
                    </div>

                    <!-- Status Selesai/Ditolak -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full {{ $surat->status == 'selesai' ? 'bg-green-500 ring-4 ring-green-200' : ($surat->status == 'ditolak' ? 'bg-red-500 ring-4 ring-red-200' : 'bg-gray-300') }} flex items-center justify-center">
                                @if($surat->status == 'selesai')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @elseif($surat->status == 'ditolak')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold">{{ $surat->status == 'selesai' ? 'Selesai' : 'Ditolak' }}</h3>
                            <p class="text-sm text-gray-500">{{ $surat->updated_at->format('d/m/Y H:i') }}</p>
                            @if($surat->status == 'selesai')
                            <p class="text-sm text-green-600">Pengajuan surat telah selesai diproses</p>
                            @elseif($surat->status == 'ditolak')
                            <p class="text-sm text-red-600">Pengajuan ditolak: {{ $surat->catatan_admin ?? 'Tidak ada keterangan' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-gray-200">
                <a href="{{ route('layanan.show', $surat->id) }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Kembali ke Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
