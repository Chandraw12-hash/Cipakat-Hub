@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">Detail Booking</h1>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Fasilitas</p>
                    <p class="font-medium">{{ $booking->item }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Kategori</p>
                    <p class="font-medium">{{ ucfirst($booking->kategori) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="font-medium">{{ $booking->tanggal_booking->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jam</p>
                    <p class="font-medium">{{ date('H:i', strtotime($booking->jam_mulai)) }} - {{ date('H:i', strtotime($booking->jam_selesai)) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jumlah</p>
                    <p class="font-medium">{{ $booking->jumlah }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Harga</p>
                    <p class="font-medium">
                        @if($booking->harga > 0)
                            <span class="text-blue-600 font-semibold">Rp {{ number_format($booking->harga, 0, ',', '.') }}</span>
                        @else
                            <span class="text-gray-500">Gratis</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Booking</p>
                    <p>{!! $booking->status_badge !!}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status Pembayaran</p>
                    <p>
                        @if($booking->status_pembayaran == 'lunas')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Lunas</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Belum Bayar</span>
                        @endif
                    </p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Keterangan</p>
                    <p class="font-medium">{{ $booking->keterangan ?? '-' }}</p>
                </div>
                @if($booking->bukti_bayar)
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Bukti Pembayaran</p>
                    <a href="{{ Storage::url($booking->bukti_bayar) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti</a>
                </div>
                @endif
                @if($booking->catatan_admin)
                <div class="col-span-2">
                    <p class="text-sm text-gray-500">Catatan Admin</p>
                    <p class="font-medium text-red-600">{{ $booking->catatan_admin }}</p>
                </div>
                @endif
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ Auth::user()->role == 'admin' || Auth::user()->role == 'petugas' ? route('booking.admin') : route('booking.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
