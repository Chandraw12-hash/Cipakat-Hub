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
                    <span class="text-sm text-gray-700 hover:text-blue-600 transition">{{ $item->judul }}</span>
                </div>
                <span class="text-xs text-gray-400">{{ $item->published_at ? $item->published_at->format('d/m') : '-' }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ==================== DASHBOARD ADMIN ==================== -->
    @if(Auth::user()->role == 'admin')

    <!-- Info Desa -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Desa {{ $namaDesa }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $alamatDesa }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400">Periode Berjalan</p>
                <p class="text-sm font-medium text-gray-700">{{ date('F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- 4 Kartu Statistik Layanan Desa -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Warga</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalWarga) }}</p>
            <p class="text-xs text-green-600 mt-1">Terdaftar di sistem</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Pengajuan Surat</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalSurat) }}</p>
            <p class="text-xs text-amber-600 mt-1">Pending: {{ $suratPending }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Produk UMKM</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalProduk) }}</p>
            <p class="text-xs text-gray-500 mt-1">Aktif: {{ $produkAktif }}</p>
        </div>
        <div class="bg-white rounded-xl border border-blue-100 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs text-blue-500 uppercase tracking-wide">Booking Aktif</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($totalBooking) }}</p>
            <p class="text-xs text-blue-500 mt-1">Pending: {{ $bookingPending }}</p>
        </div>
    </div>

    <!-- Grafik Layanan (Bar Chart) -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Grafik Layanan Desa</h3>
                <p class="text-xs text-gray-500 mt-0.5">Statistik surat & booking 6 bulan terakhir</p>
            </div>
            <div class="flex gap-3 text-xs">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Pengajuan Surat</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500"></span> Booking</span>
            </div>
        </div>
        <canvas id="layananChart" height="80"></canvas>
    </div>

    @endif

    <!-- ==================== STRUKTUR PENGURUS ==================== -->
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')

    @php
        $pengurusList = App\Models\User::whereIn('role', ['admin', 'petugas'])
            ->orderBy('role', 'asc')
            ->get()
            ->map(function($user) {
                $warna = match($user->role) {
                    'admin' => 'blue',
                    'petugas' => 'green',
                    default => 'gray'
                };
                $singkatan = match($user->role) {
                    'admin' => 'ADM',
                    'petugas' => 'PTG',
                    default => 'STF'
                };
                $jabatan = match($user->role) {
                    'admin' => 'Administrator',
                    'petugas' => 'Petugas',
                    default => 'Staff'
                };
                return (object) [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'warna' => $warna,
                    'singkatan' => $singkatan,
                    'jabatan' => $jabatan,
                    'photo' => $user->photo,
                    'phone' => $user->phone,
                ];
            });
    @endphp

    @if(count($pengurusList) > 0)
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="mb-4">
            <h3 class="font-semibold text-gray-800">Struktur Pengurus</h3>
            <p class="text-xs text-gray-500 mt-0.5">Admin & Petugas BUMDes {{ $namaDesa }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($pengurusList as $p)
            <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition">
                <div class="w-10 h-10 rounded-full bg-{{ $p->warna }}-500 flex items-center justify-center text-white font-bold text-sm overflow-hidden flex-shrink-0">
                    @if($p->photo && Storage::disk('public')->exists($p->photo))
                        <img src="{{ Storage::url($p->photo) }}" class="w-full h-full object-cover">
                    @else
                        {{ $p->singkatan }}
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-gray-800 text-sm truncate">{{ $p->nama }}</p>
                    <p class="text-xs text-{{ $p->warna }}-600">{{ $p->jabatan }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $p->email }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @endif

    <!-- ==================== DASHBOARD PETUGAS ==================== -->
    @if(Auth::user()->role == 'petugas')

    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-400">Pengajuan Surat</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSurat) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-400">Produk UMKM</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalProduk) }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl border border-blue-100 p-4">
            <p class="text-xs text-blue-500">Saldo Kas</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($saldoKas, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-amber-50 rounded-xl border border-amber-100 p-4">
            <p class="text-sm text-amber-600">Surat Pending</p>
            <p class="text-2xl font-bold text-amber-700">{{ $suratPending }}</p>
        </div>
        <div class="bg-amber-50 rounded-xl border border-amber-100 p-4">
            <p class="text-sm text-amber-600">Booking Pending</p>
            <p class="text-2xl font-bold text-amber-700">{{ $bookingPending }}</p>
        </div>
    </div>

    @endif

    <!-- ==================== DASHBOARD WARGA ==================== -->
    @if(Auth::user()->role == 'warga')

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl border border-emerald-100 p-4 text-center">
            <p class="text-xs text-emerald-600">Pengajuan Surat Saya</p>
            <p class="text-2xl font-bold text-emerald-700">{{ number_format($suratSaya) }}</p>
            <a href="{{ route('layanan.index') }}" class="inline-block mt-1 text-xs text-emerald-600 hover:underline">Lihat →</a>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-white rounded-xl border border-purple-100 p-4 text-center">
            <p class="text-xs text-purple-600">Booking Saya</p>
            <p class="text-2xl font-bold text-purple-700">{{ number_format($bookingSaya) }}</p>
            <a href="{{ route('booking.index') }}" class="inline-block mt-1 text-xs text-purple-600 hover:underline">Lihat →</a>
        </div>
    </div>

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
                <span class="text-sm font-medium text-purple-700">Booking Fasilitas</span>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('layananChart');
    if (canvas && typeof Chart !== 'undefined') {
        var labels = @json($chartLabels ?? []);
        var suratData = @json($chartSurat ?? []);
        var bookingData = @json($chartBooking ?? []);

        if (labels.length === 0) {
            labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
        }
        if (suratData.length === 0) {
            suratData = [0, 0, 0, 0, 0, 0];
        }
        if (bookingData.length === 0) {
            bookingData = [0, 0, 0, 0, 0, 0];
        }

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pengajuan Surat',
                        data: suratData,
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Booking',
                        data: bookingData,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        barPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
                    tooltip: { callbacks: { label: function(ctx) { return ctx.dataset.label + ': ' + ctx.raw + ' transaksi'; } } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: '#e5e7eb' } },
                    x: { ticks: { font: { size: 10 } }, grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endpush
