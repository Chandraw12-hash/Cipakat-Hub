@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Sambutan -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-5">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}!</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    @if(Auth::user()->role == 'admin') bg-red-100 text-red-700
                    @elseif(Auth::user()->role == 'petugas') bg-yellow-100 text-yellow-700
                    @else bg-green-100 text-green-700
                    @endif">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>
        </div>
    </div>

    <!-- ==================== PENGUMUMAN TERBARU (HANYA UNTUK WARGA) ==================== -->
    @if(Auth::user()->role == 'warga' && isset($pengumuman) && $pengumuman->count() > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <div class="flex justify-between items-center mb-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <h3 class="font-medium text-gray-700 text-sm">Pengumuman Terbaru</h3>
            </div>
            <a href="{{ route('pengumuman.index') }}" class="text-xs text-blue-500 hover:underline">Lihat Semua →</a>
        </div>
        <div class="space-y-1">
            @foreach($pengumuman->take(3) as $item)
            <a href="{{ route('pengumuman.show', $item->id) }}"
               class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full {{ $item->jenis == 'penting' ? 'bg-red-500' : 'bg-blue-500' }}"></span>
                    <span class="text-sm text-gray-700 hover:text-blue-600 transition">
                        {{ $item->judul }}
                    </span>
                </div>
                <span class="text-xs text-gray-400">{{ $item->published_at ? $item->published_at->format('d/m') : '-' }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ==================== DASHBOARD ADMIN ==================== -->
    @if(Auth::user()->role == 'admin')

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-400">Total Warga</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalWarga ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-400">Pengajuan Surat</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSurat ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-400">Produk UMKM</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalProduk ?? 0) }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-100 p-4">
            <p class="text-xs text-blue-500">Saldo Kas</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldoKas ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    @if(isset($chartLabels) && count($chartLabels) > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-700 text-sm mb-4">Grafik Keuangan 6 Bulan</h3>
        <canvas id="keuanganChart" height="80"></canvas>
    </div>
    @endif

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-amber-50 rounded-xl border border-amber-100 p-4">
            <p class="text-sm text-amber-600">Surat Pending</p>
            <p class="text-2xl font-bold text-amber-700">{{ $suratPending ?? 0 }}</p>
        </div>
        <div class="bg-amber-50 rounded-xl border border-amber-100 p-4">
            <p class="text-sm text-amber-600">Booking Pending</p>
            <p class="text-2xl font-bold text-amber-700">{{ $bookingPending ?? 0 }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl border border-gray-100 p-4">
            <p class="text-sm text-gray-500">Produk Nonaktif</p>
            <p class="text-2xl font-bold text-gray-600">{{ $produkNonaktif ?? 0 }}</p>
        </div>
    </div>

    <!-- ==================== DASHBOARD PETUGAS ==================== -->
    @elseif(Auth::user()->role == 'petugas')

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-400">Pengajuan Surat</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSurat ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-400">Produk UMKM</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalProduk ?? 0) }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-100 p-4">
            <p class="text-xs text-blue-500">Saldo Kas</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldoKas ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    @if(isset($chartLabels) && count($chartLabels) > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-700 text-sm mb-4">Grafik Keuangan 6 Bulan</h3>
        <canvas id="keuanganChart" height="80"></canvas>
    </div>
    @endif

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-amber-50 rounded-xl border border-amber-100 p-4">
            <p class="text-sm text-amber-600">Surat Pending</p>
            <p class="text-2xl font-bold text-amber-700">{{ $suratPending ?? 0 }}</p>
        </div>
        <div class="bg-amber-50 rounded-xl border border-amber-100 p-4">
            <p class="text-sm text-amber-600">Booking Pending</p>
            <p class="text-2xl font-bold text-amber-700">{{ $bookingPending ?? 0 }}</p>
        </div>
    </div>

    <!-- ==================== DASHBOARD WARGA ==================== -->
    @else

    <!-- Statistik Saya -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl border border-emerald-100 p-4 text-center">
            <p class="text-xs text-emerald-600">Pengajuan Surat Saya</p>
            <p class="text-2xl font-bold text-emerald-700">{{ number_format($suratSaya ?? 0) }}</p>
            <a href="{{ route('layanan.index') }}" class="inline-block mt-1 text-xs text-emerald-600 hover:underline">Lihat →</a>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-white rounded-xl border border-purple-100 p-4 text-center">
            <p class="text-xs text-purple-600">Booking Saya</p>
            <p class="text-2xl font-bold text-purple-700">{{ number_format($bookingSaya ?? 0) }}</p>
            <a href="{{ route('booking.index') }}" class="inline-block mt-1 text-xs text-purple-600 hover:underline">Lihat →</a>
        </div>
    </div>

    <!-- Menu Cepat Warga -->
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <h3 class="font-medium text-gray-700 text-sm mb-3">Layanan Cepat</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('layanan.create') }}" class="flex items-center justify-between bg-blue-50 hover:bg-blue-100 p-3 rounded-lg transition">
                <span class="text-sm font-medium text-blue-700">Ajukan Surat</span>
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('booking.create') }}" class="flex items-center justify-between bg-purple-50 hover:bg-purple-100 p-3 rounded-lg transition">
                <span class="text-sm font-medium text-purple-700">Booking</span>
                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    @endif

</div>
@endsection

@push('scripts')
@if(Auth::user()->role != 'warga')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('keuanganChart');
        if (canvas && typeof Chart !== 'undefined') {
            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: @json($chartLabels ?? []),
                    datasets: [
                        { label: 'Pemasukan', data: @json($chartPemasukan ?? []), borderColor: '#10b981', borderWidth: 2, fill: false, tension: 0.3 },
                        { label: 'Pengeluaran', data: @json($chartPengeluaran ?? []), borderColor: '#ef4444', borderWidth: 2, fill: false, tension: 0.3 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
                        tooltip: { callbacks: { label: function(ctx) { return ctx.dataset.label + ': Rp ' + ctx.raw.toLocaleString('id-ID'); } } }
                    },
                    scales: { y: { beginAtZero: true, ticks: { callback: function(v) { return 'Rp ' + (v/1000).toFixed(0) + 'k'; } } } }
                }
            });
        }
    });
</script>
@endif
@endpush
