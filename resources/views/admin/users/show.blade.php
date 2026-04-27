@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="py-6">

    <!-- Navigation Breadcrumb -->
    <div class="max-w-6xl mx-auto px-4 mb-6">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-gray-700 transition">Manajemen User</a>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800 font-medium">Detail User</span>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail User</h1>
                <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap profil pengguna</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('users.edit', $user->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit User
                </a>
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <!-- Header Profile -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-5">
                    <!-- Avatar -->
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden shadow-sm">
                        @if($user->photo)
                            <img src="{{ Storage::url($user->photo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-semibold text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <!-- Info -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="text-sm text-gray-500">{{ $user->email }}</span>
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full
                                @if($user->role == 'admin') bg-red-100 text-red-700
                                @elseif($user->role == 'petugas') bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5">Terdaftar {{ $user->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Body Content -->
            <div class="p-6">

                <!-- 3 Kolom Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Kolom 1: Data Diri -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-800 text-sm">Data Diri</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400">NIK</p>
                                <p class="text-sm text-gray-800 font-mono">{{ $user->nik ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">No. Telepon</p>
                                <p class="text-sm text-gray-800">{{ $user->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Tempat, Tanggal Lahir</p>
                                <p class="text-sm text-gray-800">{{ $user->tempat_lahir ?? '-' }} {{ $user->tanggal_lahir ? ', ' . $user->tanggal_lahir->format('d/m/Y') : '' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Jenis Kelamin</p>
                                <p class="text-sm text-gray-800">{{ $user->jenis_kelamin ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 2: Pekerjaan & Pendidikan -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-800 text-sm">Pekerjaan & Pendidikan</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Pekerjaan</p>
                                <p class="text-sm text-gray-800">{{ $user->pekerjaan ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Status Pekerjaan</p>
                                <p>
                                    @if($user->status_pekerjaan == 'bekerja')
                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">Bekerja</span>
                                    @elseif($user->status_pekerjaan == 'tidak_bekerja')
                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700">Tidak Bekerja</span>
                                    @elseif($user->status_pekerjaan == 'mahasiswa')
                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">Mahasiswa</span>
                                    @elseif($user->status_pekerjaan == 'pensiun')
                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700">Pensiun</span>
                                    @else
                                        <span class="text-sm text-gray-500">{{ $user->status_pekerjaan ?? '-' }}</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Pendidikan Terakhir</p>
                                <p class="text-sm text-gray-800">{{ $user->pendidikan_terakhir ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Pendapatan Bulanan</p>
                                <p class="text-sm text-gray-800">Rp {{ number_format($user->pendapatan_bulanan ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 3: Sosial Ekonomi -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-800 text-sm">Sosial Ekonomi</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Kategori Sosial</p>
                                <p>
                                    @if($user->kategori_sosial == 'miskin')
                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700">Miskin</span>
                                    @elseif($user->kategori_sosial == 'rentan')
                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700">Rentan</span>
                                    @elseif($user->kategori_sosial == 'mampu')
                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">Mampu</span>
                                    @else
                                        <span class="text-sm text-gray-500">{{ $user->kategori_sosial ?? '-' }}</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Status Rumah</p>
                                <p class="text-sm text-gray-800">{{ $user->status_rumah ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Penerima Bantuan</p>
                                <p class="text-sm text-gray-800">{{ $user->is_penerima_bantuan ? 'Ya' : 'Tidak' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Jumlah Tanggungan</p>
                                <p class="text-sm text-gray-800">{{ $user->jumlah_tanggungan ?? 0 }} orang</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100 my-6"></div>

                <!-- Baris 2: Keluarga & Alamat (2 kolom) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Keluarga -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <div class="w-7 h-7 rounded-lg bg-purple-50 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-800 text-sm">Data Keluarga</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Status Keluarga</p>
                                <p class="text-sm text-gray-800">
                                    @if($user->status_keluarga == 'kepala_keluarga')
                                        Kepala Keluarga
                                    @elseif($user->status_keluarga == 'istri')
                                        Istri
                                    @elseif($user->status_keluarga == 'anak')
                                        Anak
                                    @else
                                        {{ ucfirst($user->status_keluarga ?? 'Lainnya') }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">NIK Kepala Keluarga</p>
                                <p class="text-sm text-gray-800 font-mono">{{ $user->kepala_keluarga_nik ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-medium text-gray-800 text-sm">Alamat</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400">Alamat Lengkap</p>
                                <p class="text-sm text-gray-800">{{ $user->alamat ?? '-' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-400">RT / RW</p>
                                    <p class="text-sm text-gray-800">{{ $user->rt_rw ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Kode Pos</p>
                                    <p class="text-sm text-gray-800">{{ $user->kode_pos ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timestamp -->
                <div class="mt-6 pt-4 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Dibuat: {{ $user->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Terakhir update: {{ $user->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
