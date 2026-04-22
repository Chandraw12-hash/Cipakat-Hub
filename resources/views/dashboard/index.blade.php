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

    <!-- Info BUMDes -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">BUMDes {{ $namaDesa }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $alamatDesa }}</p>
                <p class="text-xs text-gray-400 mt-1">AHL: {{ $ahl }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400">Periode Berjalan</p>
                <p class="text-sm font-medium text-gray-700">{{ date('F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- 4 Kartu Utama -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Aset</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalAset, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Pasiva</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalPasiva, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Total Modal</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalModal, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-blue-100 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-xs text-blue-500 uppercase tracking-wide">Laba/Rugi (Tahun Ini)</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($labaRugiTahunIni, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Grafik Kinerja -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Grafik Kinerja</h3>
                <p class="text-xs text-gray-500 mt-0.5">Analisis Pendapatan, Biaya, dan Laba</p>
            </div>
            <div class="flex gap-3 text-xs">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500"></span> Pendapatan</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500"></span> Biaya</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Laba</span>
            </div>
        </div>
        <canvas id="kinerjaChart" height="100"></canvas>
    </div>

    <!-- 3 Kartu Informasi -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Total Warga</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalWarga) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Pengajuan Surat</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSurat) }}</p>
            <p class="text-xs text-amber-600 mt-1">Pending: {{ $suratPending }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Produk UMKM</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalProduk) }}</p>
            <p class="text-xs text-gray-500 mt-1">Aktif: {{ $produkAktif }}</p>
        </div>
    </div>

    @endif

    <!-- ==================== STRUKTUR PENGURUS (UNTUK ADMIN & PETUGAS) DARI DATABASE ==================== -->
    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')

    @php
        // Ambil data pengurus dari database users (admin & petugas)
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
                    'admin' => 'Administrator BUMDes',
                    'petugas' => 'Petugas BUMDes',
                    default => 'Staff BUMDes'
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
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Struktur Pengurus</h3>
                <p class="text-xs text-gray-500 mt-0.5">Admin & Petugas BUMDes {{ $namaDesa }}</p>
            </div>
            <div class="flex gap-2" id="swipeButtons">
                <button id="prevBtn" class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 transition text-gray-600 text-sm">← Sebelumnya</button>
                <button id="nextBtn" class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 transition text-gray-600 text-sm">Selanjutnya →</button>
            </div>
        </div>

        <div class="overflow-x-auto relative" id="pengurusContainer">
            <div class="flex gap-6 transition-transform duration-300" id="pengurusSlider">
                @foreach($pengurusList as $p)
                <div class="min-w-[280px] bg-gradient-to-br from-{{ $p->warna }}-50 to-white rounded-xl border border-{{ $p->warna }}-100 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-{{ $p->warna }}-500 flex items-center justify-center text-white text-xl font-bold shadow-md overflow-hidden">
                            @if($p->photo)
                                <img src="{{ Storage::url($p->photo) }}" class="w-full h-full object-cover">
                            @else
                                {{ $p->singkatan }}
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-lg">{{ $p->nama }}</p>
                            <p class="text-sm text-{{ $p->warna }}-600 font-semibold">{{ $p->jabatan }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $p->email }}</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-{{ $p->warna }}-100">
                        <p class="text-xs text-gray-500">
                            @if($p->phone) 📞 {{ $p->phone }} @else 📧 Aktif di sistem @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-center gap-2 mt-4" id="swipeDots">
            @foreach($pengurusList as $idx => $p)
                <span class="swipe-dot w-2 h-2 rounded-full {{ $idx == 0 ? 'bg-blue-600 w-4' : 'bg-gray-300' }} transition-all cursor-pointer" data-index="{{ $idx }}"></span>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('pengurusSlider');
            const container = document.getElementById('pengurusContainer');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const dots = document.querySelectorAll('.swipe-dot');

            if (!slider || !container) return;

            let currentIndex = 0;
            let itemWidth = 0;
            let visibleItems = 0;
            const totalItems = {{ count($pengurusList) }};

            function updateSlider() {
                const containerWidth = container.clientWidth;
                const firstItem = slider.querySelector('.min-w-\\[280px\\]');
                if (firstItem) {
                    itemWidth = firstItem.offsetWidth + 24;
                    visibleItems = Math.floor(containerWidth / itemWidth);
                    if (visibleItems < 1) visibleItems = 1;

                    const maxIndex = Math.max(0, totalItems - visibleItems);
                    currentIndex = Math.min(currentIndex, maxIndex);

                    const translateX = -currentIndex * itemWidth;
                    slider.style.transform = `translateX(${translateX}px)`;

                    dots.forEach((dot, idx) => {
                        if (idx === currentIndex) {
                            dot.classList.remove('bg-gray-300');
                            dot.classList.add('bg-blue-600');
                            dot.classList.add('w-4');
                        } else {
                            dot.classList.add('bg-gray-300');
                            dot.classList.remove('bg-blue-600');
                            dot.classList.remove('w-4');
                        }
                    });

                    if (prevBtn) {
                        prevBtn.disabled = currentIndex === 0;
                        prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                    }
                    if (nextBtn) {
                        nextBtn.disabled = currentIndex >= maxIndex;
                        nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
                    }
                }
            }

            if (nextBtn) nextBtn.onclick = () => {
                const maxIndex = Math.max(0, totalItems - visibleItems);
                if (currentIndex < maxIndex) { currentIndex++; updateSlider(); }
            };

            if (prevBtn) prevBtn.onclick = () => {
                if (currentIndex > 0) { currentIndex--; updateSlider(); }
            };

            dots.forEach((dot, idx) => {
                dot.onclick = () => {
                    const maxIndex = Math.max(0, totalItems - visibleItems);
                    if (idx <= maxIndex) { currentIndex = idx; updateSlider(); }
                };
            });

            window.addEventListener('resize', function() { setTimeout(updateSlider, 100); });
            setTimeout(updateSlider, 100);
        });
    </script>
    @else
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm text-center">
        <p class="text-gray-500">Belum ada data pengurus</p>
        <p class="text-xs text-gray-400 mt-1">Tambahkan admin atau petugas melalui Manajemen User</p>
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

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-700 text-sm mb-4">Grafik Keuangan 6 Bulan</h3>
        <canvas id="keuanganChart" height="100"></canvas>
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
@if(Auth::user()->role == 'admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('kinerjaChart');
        if (canvas && typeof Chart !== 'undefined') {
            const labels = @json($chartLabels);
            const pendapatan = @json($chartPendapatan);
            const biaya = @json($chartBiaya);
            const laba = @json($chartLaba);

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [
                        { label: 'Pendapatan', data: pendapatan.length ? pendapatan : [0, 0, 0, 0, 0, 0], borderColor: '#10b981', borderWidth: 2, fill: false, tension: 0.3 },
                        { label: 'Biaya', data: biaya.length ? biaya : [0, 0, 0, 0, 0, 0], borderColor: '#ef4444', borderWidth: 2, fill: false, tension: 0.3 },
                        { label: 'Laba', data: laba.length ? laba : [0, 0, 0, 0, 0, 0], borderColor: '#3b82f6', borderWidth: 2, fill: false, tension: 0.3 }
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

@if(Auth::user()->role == 'petugas')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('keuanganChart');
        if (canvas && typeof Chart !== 'undefined') {
            const labels = @json($chartLabels);
            const pemasukan = @json($chartPemasukan);
            const pengeluaran = @json($chartPengeluaran);

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [
                        { label: 'Pemasukan', data: pemasukan.length ? pemasukan : [0, 0, 0, 0, 0, 0], borderColor: '#10b981', borderWidth: 2, fill: false, tension: 0.3 },
                        { label: 'Pengeluaran', data: pengeluaran.length ? pengeluaran : [0, 0, 0, 0, 0, 0], borderColor: '#ef4444', borderWidth: 2, fill: false, tension: 0.3 }
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
