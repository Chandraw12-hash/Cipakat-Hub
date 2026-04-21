@extends('layouts.app')

@section('title', 'Pembayaran Booking')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h1 class="text-lg font-semibold text-gray-800">Pembayaran Booking</h1>
            <p class="text-sm text-gray-500 mt-0.5">Selesaikan pembayaran untuk konfirmasi booking</p>
        </div>

        <div class="p-6">
            <!-- Info Total & Kode Booking -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Total Pembayaran</span>
                    <span class="text-xl font-bold text-blue-600">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Kode Booking</span>
                    <span class="text-sm font-semibold text-gray-800">#{{ $bookingId }}</span>
                </div>
                <div class="flex justify-between items-center mt-2 pt-2 border-t border-blue-200">
                    <span class="text-sm text-gray-600">Status</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">Menunggu Pembayaran</span>
                </div>
            </div>

            <!-- QR Code -->
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 mb-3">Scan QR Code di bawah untuk membayar</p>
                <div class="bg-white p-4 rounded-lg border border-gray-200 inline-block">
                    <img src="{{ asset('images/qris.png') }}" alt="QRIS" class="w-48 mx-auto">
                </div>
                <p class="text-xs text-gray-500 mt-2">Gunakan aplikasi mobile banking atau e-wallet</p>
            </div>

            <!-- Upload Bukti -->
            <div class="border-t border-gray-200 pt-6">
                <p class="text-sm font-medium text-gray-700 mb-3">Upload Bukti Pembayaran</p>
                <form action="{{ route('booking.uploadBukti', $bookingId) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="bukti_bayar" id="bukti_bayar" accept="image/*" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB</p>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>

            <!-- Link Kembali -->
            <div class="mt-6 pt-4 text-center">
                <a href="{{ route('booking.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                    Kembali ke Booking Saya
                </a>
            </div>
        </div>
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
            timer: 3000
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

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: '{!! implode('<br>', $errors->all()) !!}',
            confirmButtonColor: '#d33'
        });
    @endif

    // Konfirmasi sebelum upload
    document.getElementById('uploadForm')?.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('bukti_bayar');
        if (fileInput && !fileInput.files.length) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Silakan pilih file bukti pembayaran terlebih dahulu!',
                confirmButtonColor: '#3085d6'
            });
        }
    });
</script>
@endsection
