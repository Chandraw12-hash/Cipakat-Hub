@extends('layouts.app')

@section('title', 'Manajemen Booking')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Booking</h1>
        <p class="text-gray-500 mt-1">Kelola booking fasilitas desa</p>
    </div>

    <!-- Filter Status -->
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('booking.admin') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Semua</a>
        <a href="{{ route('booking.admin', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Pending</a>
        <a href="{{ route('booking.admin', ['status' => 'confirmed']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'confirmed' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Dikonfirmasi</a>
        <a href="{{ route('booking.admin', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'cancelled' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Dibatalkan</a>
        <a href="{{ route('booking.admin', ['status' => 'selesai']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == 'selesai' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">Selesai</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Pemesan</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Item</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Jam</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Bukti Bayar</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bookings as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm">{{ $item->tanggal_booking->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $item->user->name }}</td>
                        <td class="px-6 py-4 font-medium">{{ $item->item }}</td>
                        <td class="px-6 py-4 text-sm">{{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}</td>
                        <td class="px-6 py-4">
                            @if($item->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                            @elseif($item->status == 'confirmed')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Dikonfirmasi</span>
                            @elseif($item->status == 'cancelled')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Dibatalkan</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->bukti_bayar)
                                <a href="{{ Storage::url($item->bukti_bayar) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Lihat Bukti</a>
                            @else
                                <span class="text-gray-400 text-sm">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('booking.show', $item->id) }}" class="text-blue-600 hover:text-blue-800">Detail</a>
                                @if($item->status == 'pending')
                                    <form action="{{ route('booking.confirm', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800">Konfirmasi</button>
                                    </form>
                                    <button onclick="showCancelModal({{ $item->id }})" class="text-red-600 hover:text-red-800">Batal</button>
                                @endif
                                @if($item->status == 'confirmed')
                                    <form action="{{ route('booking.complete', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-800">Selesai</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada booking</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showCancelModal(id) {
    Swal.fire({
        title: 'Batalkan Booking',
        input: 'text',
        inputLabel: 'Masukkan alasan pembatalan',
        inputPlaceholder: 'Alasan...',
        showCancelButton: true,
        confirmButtonText: 'Batalkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage('Alasan harus diisi!');
                return false;
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '/booking/' + id + '/cancel';
            let csrf = document.createElement('input');
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            let reasonInput = document.createElement('input');
            reasonInput.name = 'catatan_admin';
            reasonInput.value = result.value;
            form.appendChild(csrf);
            form.appendChild(reasonInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// SweetAlert untuk notifikasi session
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
</script>
@endsection
