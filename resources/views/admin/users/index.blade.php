@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-gray-500 mt-1">Kelola data pengguna sistem (warga, petugas, admin)</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Total User</p>
            <p class="text-xl font-bold text-gray-800">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Admin</p>
            <p class="text-xl font-bold text-red-600">{{ $totalAdmin }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Petugas</p>
            <p class="text-xl font-bold text-yellow-600">{{ $totalPetugas }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Warga</p>
            <p class="text-xl font-bold text-green-600">{{ $totalWarga }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Belum Lengkap</p>
            <p class="text-xl font-bold text-orange-600">{{ $totalBelumLengkap }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Nama atau email..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-40">
                <label class="block text-xs font-medium text-gray-500 mb-1">Filter Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    <option value="warga" {{ request('role') == 'warga' ? 'selected' : '' }}>Warga</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Filter
                </button>
                <a href="{{ route('users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition ml-2">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel User -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-semibold text-gray-800">Daftar Pengguna</h3>
            <p class="text-xs text-gray-500 mt-0.5">Semua pengguna yang terdaftar di sistem</p>
        </div>

        @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Foto</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Nama / Role</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Kontak</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">NIK</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Alamat</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $index => $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600">{{ $index + $users->firstItem() }}</td>
                        <td class="px-4 py-3">
                            @if($user->photo)
                                <img src="{{ Storage::url($user->photo) }}" class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm
                                    @if($user->role == 'admin') bg-red-500
                                    @elseif($user->role == 'petugas') bg-yellow-500
                                    @else bg-green-500
                                    @endif">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                            </div>
                            <span class="inline-flex mt-1 px-2 py-0.5 text-xs font-semibold rounded-full
                                @if($user->role == 'admin') bg-red-100 text-red-700
                                @elseif($user->role == 'petugas') bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-600">
                                @if($user->phone)
                                    <div class="flex items-center gap-1 text-xs">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $user->phone }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $user->nik ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-gray-600 text-xs">
                                @if($user->alamat)
                                    <div>{{ Str::limit($user->alamat, 30) }}</div>
                                    @if($user->rt_rw)
                                        <div class="text-gray-400">RT/RW: {{ $user->rt_rw }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="text-blue-600 hover:text-blue-800 transition p-1" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if(Auth::id() != $user->id)
                                <button type="button" class="delete-btn text-red-600 hover:text-red-800 transition p-1" title="Hapus" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="text-gray-500">Belum ada data user</p>
            <p class="text-sm text-gray-400 mt-1">Klik "Tambah User" untuk menambahkan pengguna baru</p>
        </div>
        @endif
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Notifikasi sukses dari session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            timer: 3000,
            showConfirmButton: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33'
        });
    @endif

    // Konfirmasi hapus dengan SweetAlert
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const id = this.dataset.id;
            const name = this.dataset.name;

            Swal.fire({
                title: 'Hapus User?',
                html: `Apakah Anda yakin ingin menghapus user <strong>${name}</strong>?<br>Data yang dihapus tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endsection
