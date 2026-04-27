@extends('layouts.app')

@section('title', 'Analisis Warga')

@section('content')
    <div class="p-6 font-sans bg-gray-50 min-h-screen">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Analisis Kependudukan</h1>
                <p class="text-sm text-gray-500">Dashboard statistik warga Desa Cipakat</p>
            </div>

            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('analisis.warga.export') }}"
                    class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg shadow-sm hover:bg-green-700 transition">
                    Export Excel
                </a>

                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                    Kelola Data
                </a>
            </div>
        </div>

        {{-- STAT --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <p class="text-xs text-gray-400 uppercase">Total Warga</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ number_format($totalWarga) }}</h2>
            </div>

            <div class="bg-blue-50 p-5 rounded-xl border shadow-sm">
                <p class="text-xs text-blue-500 uppercase">Laki-laki</p>
                <h2 class="text-2xl font-bold text-blue-700">{{ number_format($totalLakiLaki) }}</h2>
            </div>

            <div class="bg-pink-50 p-5 rounded-xl border shadow-sm">
                <p class="text-xs text-pink-500 uppercase">Perempuan</p>
                <h2 class="text-2xl font-bold text-pink-700">{{ number_format($totalPerempuan) }}</h2>
            </div>

            <div class="bg-green-50 p-5 rounded-xl border shadow-sm">
                <p class="text-xs text-green-500 uppercase">Penerima Bantuan</p>
                <h2 class="text-2xl font-bold text-green-700">{{ number_format($penerimaBantuan) }}</h2>
            </div>

        </div>

        {{-- CHART --}}
        <div class="grid md:grid-cols-3 gap-4 mb-6">

            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <h3 class="text-sm font-semibold mb-3">Status Pekerjaan</h3>
                <div class="relative h-[220px]">
                    <canvas id="pekerjaanChart" class="absolute inset-0 w-full h-full"></canvas>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <h3 class="text-sm font-semibold mb-3">Kategori Sosial</h3>
                <div class="relative h-[220px]">
                    <canvas id="sosialChart" class="absolute inset-0 w-full h-full"></canvas>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <h3 class="text-sm font-semibold mb-3">Pendidikan</h3>
                <div class="relative h-[220px]">
                    <canvas id="pendidikanChart" class="absolute inset-0 w-full h-full"></canvas>
                </div>
            </div>

        </div>

        {{-- BOTTOM --}}
        <div class="grid md:grid-cols-[300px_1fr] gap-4">

            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <h3 class="text-sm font-semibold mb-3">Jenis Kelamin</h3>
                <div class="relative h-[200px]">
                    <canvas id="genderChart" class="absolute inset-0 w-full h-full"></canvas>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border shadow-sm">
                <h3 class="text-sm font-semibold mb-3">Ringkasan & Insight</h3>

                <p class="text-sm text-gray-600 leading-relaxed">
    Dari total <strong>{{ $totalKK }}</strong> Kepala Keluarga,
    terdapat <strong class="text-red-600">{{ $totalKKMiskin }}</strong> KK dengan pendapatan di bawah Rp 1.000.000 (miskin),
    <strong class="text-yellow-600">{{ $totalKKRentan }}</strong> KK dengan pendapatan Rp 1-2 juta (rentan),
    dan <strong class="text-green-600">{{ $totalKKMampu }}</strong> KK dengan pendapatan di atas Rp 2 juta (mampu).
</p>

<p class="text-sm text-gray-600 mt-2 leading-relaxed">
    Mayoritas tingkat pendidikan Kepala Keluarga berada pada kategori
    <strong>{{ strtoupper($pendidikanDominan) }}</strong>.
</p>

                {{-- ========================= --}}
                {{-- REKOMENDASI BANTUAN --}}
                {{-- ========================= --}}
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm font-semibold text-yellow-700 mb-2">
                        Rekomendasi Prioritas Bantuan:
                    </p>

                    <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                        <li>
                            Warga kategori <strong>miskin</strong> dengan pendapatan rendah
                        </li>
                        <li>
                            Warga <strong>tidak bekerja</strong> tanpa penghasilan tetap
                        </li>
                        <li>
                            Warga dengan <strong>tanggungan keluarga tinggi</strong>
                        </li>
                    </ul>
                </div>

                {{-- ========================= --}}
                {{-- TARGET REAL DATA (PAKAI prioritasBantuan) --}}
                {{-- ========================= --}}
                @if(isset($prioritasBantuan) && $prioritasBantuan->count() > 0)
                    <div class="mt-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">
                            Contoh Warga Prioritas:
                        </p>

                        <ul class="text-sm text-gray-600 space-y-1">
                            @foreach ($prioritasBantuan->take(3) as $w)
                                <li>
                                    • {{ $w->name }}
                                    (Rp {{ number_format($w->total_pendapatan_kk ?? $w->pendapatan_bulanan ?? 0) }} / bulan)
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="text-sm text-gray-600 mt-4 leading-relaxed">
                    Berdasarkan analisis ini, disarankan pemerintah desa untuk fokus pada
                    <strong>bantuan sosial langsung</strong> dan <strong>program pelatihan kerja</strong>
                    guna meningkatkan kesejahteraan warga secara berkelanjutan.
                </p>
            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#6b7280';

            // =========================
            // DATA PEKERJAAN (FIX LABEL)
            // =========================
            const pekerjaanLabels = [];
            const pekerjaanValues = [];

            @foreach ($pekerjaanData as $k => $v)
                @if ($k !== 'belum_diisi' && $v > 0)
                    pekerjaanLabels.push(
                        "{{ $k == 'bekerja' ? 'Bekerja' : ($k == 'tidak_bekerja' ? 'Tidak Bekerja' : ($k == 'mahasiswa' ? 'Mahasiswa' : 'Pensiun')) }}"
                    );
                    pekerjaanValues.push({{ $v }});
                @endif
            @endforeach

            // =========================
            // DATA SOSIAL (FIX LABEL)
            // =========================
            const sosialLabels = [];
            const sosialValues = [];

            @foreach ($sosialData as $k => $v)
                @if ($v > 0)
                    sosialLabels.push(
                        "{{ $k == 'miskin' ? 'Miskin' : ($k == 'rentan' ? 'Rentan' : ($k == 'hampir miskin' ? 'Hampir Miskin' : 'Mampu')) }}"
                    );
                    sosialValues.push({{ $v }});
                @endif
            @endforeach

            // =========================
            // DATA PENDIDIKAN
            // =========================
            const pendidikanLabels = [];
            const pendidikanValues = [];

            @foreach ($pendidikanData as $k => $v)
                @if ($v > 0)
                    pendidikanLabels.push("{{ strtoupper($k) }}");
                    pendidikanValues.push({{ $v }});
                @endif
            @endforeach

            // =========================
            // CHART PEKERJAAN
            // =========================
            new Chart(document.getElementById('pekerjaanChart'), {
                type: 'bar',
                data: {
                    labels: pekerjaanLabels,
                    datasets: [{
                        data: pekerjaanValues,
                        backgroundColor: ['#10b981', '#ef4444', '#3b82f6', '#f59e0b'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.raw} orang`
                            }
                        }
                    }
                }
            });

            // =========================
            // CHART SOSIAL
            // =========================
            new Chart(document.getElementById('sosialChart'), {
                type: 'doughnut',
                data: {
                    labels: sosialLabels,
                    datasets: [{
                        data: sosialValues,
                        backgroundColor: ['#ef4444', '#f59e0b', '#f97316', '#10b981'],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: ${ctx.raw}`
                            }
                        }
                    }
                }
            });

            // =========================
            // CHART PENDIDIKAN
            // =========================
            new Chart(document.getElementById('pendidikanChart'), {
                type: 'bar',
                data: {
                    labels: pendidikanLabels,
                    datasets: [{
                        data: pendidikanValues,
                        backgroundColor: '#3b82f6',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // =========================
            // CHART GENDER
            // =========================
            new Chart(document.getElementById('genderChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Laki-laki', 'Perempuan'],
                    datasets: [{
                        data: [{{ $totalLakiLaki }}, {{ $totalPerempuan }}],
                        backgroundColor: ['#3b82f6', '#ec4899']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%'
                }
            });

        });
    </script>
@endpush
