@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Booking Saya</h1>
            <p class="text-gray-500 mt-1">Riwayat booking fasilitas desa</p>
        </div>
        <a href="{{ route('booking.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Booking Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Fasilitas</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Jam</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Harga</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($bookings as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm">{{ $item->tanggal_booking->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm font-medium">{{ $item->item }}</td>
                            <td class="px-4 py-3 text-sm">{{ date('H:i', strtotime($item->jam_mulai)) }} - {{ date('H:i', strtotime($item->jam_selesai)) }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if($item->harga > 0)
                                    <span class="font-semibold text-blue-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-400">Gratis</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
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
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('booking.show', $item->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Detail</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-500">Belum ada booking</p>
                <a href="{{ route('booking.create') }}" class="mt-3 inline-block text-blue-600 hover:underline">Booking sekarang</a>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
