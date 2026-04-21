@extends('layouts.app')

@section('title', 'Manajemen Pengajuan Surat')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengajuan Surat</h1>
        <p class="text-gray-500 mt-1">Kelola pengajuan surat menyurat dari warga</p>
    </div>

    <!-- Filter Status -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('layanan.admin') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Semua</a>
        <a href="{{ route('layanan.admin', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Pending</a>
        <a href="{{ route('layanan.admin', ['status' => 'diproses']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'diproses' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Diproses</a>
        <a href="{{ route('layanan.admin', ['status' => 'selesai']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'selesai' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Selesai</a>
        <a href="{{ route('layanan.admin', ['status' => 'ditolak']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'ditolak' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Ditolak</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Pemohon</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Jenis Surat</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($surats as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $item->user->name }}</td>
                        <td class="px-6 py-4 font-medium">{{ $item->jenis_surat }}</td>
                        <td class="px-6 py-4">{!! $item->status_badge !!}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('layanan.show', $item->id) }}" class="text-blue-600 hover:text-blue-800 transition">Detail</a>
                                @if($item->status == 'pending')
                                    <button type="button" class="process-btn text-green-600 hover:text-green-800 transition"
                                            data-id="{{ $item->id }}" data-url="{{ route('layanan.approve', $item->id) }}">
                                        Proses
                                    </button>
                                    <button type="button" class="reject-btn text-red-600 hover:text-red-800 transition"
                                            data-id="{{ $item->id }}" data-url="{{ route('layanan.reject', $item->id) }}">
                                        Tolak
                                    </button>
                                @endif
                                @if($item->status == 'diproses')
                                    <button type="button" class="complete-btn text-blue-600 hover:text-blue-800 transition"
                                            data-id="{{ $item->id }}" data-url="{{ route('layanan.complete', $item->id) }}">
                                        Selesaikan
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500">Belum ada pengajuan surat</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $surats->links() }}
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Notifikasi dari session
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

    // Proses surat (approve)
    document.querySelectorAll('.process-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            Swal.fire({
                title: 'Proses Pengajuan?',
                text: 'Pengajuan surat akan diproses. Lanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form via fetch
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        window.location.reload();
                    });
                }
            });
        });
    });

    // Tolak surat dengan alasan (modal input)
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            Swal.fire({
                title: 'Tolak Pengajuan',
                html: `
                    <input type="text" id="alasan" class="swal2-input" placeholder="Masukkan alasan penolakan">
                `,
                showCancelButton: true,
                confirmButtonText: 'Tolak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                preConfirm: () => {
                    const alasan = Swal.getPopup().querySelector('#alasan').value;
                    if (!alasan) {
                        Swal.showValidationMessage('Alasan penolakan harus diisi!');
                        return false;
                    }
                    return alasan;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form dengan alasan
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ catatan_admin: result.value })
                    }).then(response => {
                        window.location.reload();
                    });
                }
            });
        });
    });

    // Selesaikan surat (complete)
    document.querySelectorAll('.complete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            Swal.fire({
                title: 'Selesaikan Pengajuan?',
                text: 'Pengajuan surat akan ditandai selesai. Lanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        window.location.reload();
                    });
                }
            });
        });
    });
</script>
@endsection
