@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan</h1>
        <p class="text-gray-500 mt-1">Generate laporan sistem BUMDes</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Pemasukan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Pengajuan Surat</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalSurat }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Booking</p>
            <p class="text-2xl font-bold text-purple-600">{{ $totalBooking }}</p>
        </div>
    </div>

    <!-- Menu Laporan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Laporan Keuangan -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white">
                <h3 class="font-semibold text-gray-800">Laporan Keuangan</h3>
                <p class="text-sm text-gray-500 mt-1">Pemasukan & Pengeluaran</p>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('laporan.keuangan') }}" class="block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Lihat Laporan
                </a>
                <a href="{{ route('laporan.export.keuangan') }}" class="block text-center border border-green-600 text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Export PDF
                </a>
            </div>
        </div>

        <!-- Laporan Surat -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                <h3 class="font-semibold text-gray-800">Laporan Surat</h3>
                <p class="text-sm text-gray-500 mt-1">Pengajuan Layanan Surat</p>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('laporan.surat') }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Lihat Laporan
                </a>
                <a href="{{ route('laporan.export.surat') }}" class="block text-center border border-blue-600 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Export PDF
                </a>
            </div>
        </div>

        <!-- Laporan Booking -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-white">
                <h3 class="font-semibold text-gray-800">Laporan Booking</h3>
                <p class="text-sm text-gray-500 mt-1">Reservasi & Pemesanan</p>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('laporan.booking') }}" class="block text-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Lihat Laporan
                </a>
                <a href="{{ route('laporan.export.booking') }}" class="block text-center border border-purple-600 text-purple-600 hover:bg-purple-50 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Export PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
