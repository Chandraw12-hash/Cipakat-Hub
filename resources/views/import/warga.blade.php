@extends('layouts.app')

@section('title', 'Import Data Warga')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h1 class="text-xl font-bold text-gray-800">Import Data Warga</h1>
            <p class="text-gray-500 text-sm mt-1">Upload file Excel untuk menambahkan banyak warga sekaligus</p>
        </div>

        <div class="p-6 space-y-5">
            <!-- Alert Info -->
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-semibold text-blue-800">Panduan Import</p>
                </div>
                <ul class="text-sm text-blue-700 space-y-1 ml-6 list-disc">
                    <li>Download template Excel terlebih dahulu</li>
                    <li>Isi data warga sesuai kolom yang tersedia</li>
                    <li>Baris pertama (header) JANGAN DIHAPUS</li>
                    <li><strong>NIK</strong> dan <strong>Nama Lengkap</strong> wajib diisi</li>
                    <li>Email dan Password bisa dikosongkan (akan diisi otomatis)</li>
                    <li>Format tanggal lahir: <code>YYYY-MM-DD</code> (contoh: 1990-05-15)</li>
                    <li>Pastikan tidak ada NIK yang duplikat</li>
                </ul>
            </div>

            <!-- Download Template -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">Download Template Excel</p>
                        <p class="text-xs text-gray-500 mt-1">File template 21 kolom sesuai database</p>
                    </div>
                    <a href="{{ route('import.warga.template') }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Template
                    </a>
                </div>
            </div>

            <!-- Upload File -->
            <form action="{{ route('import.warga.store') }}" method="POST" enctype="multipart/form-data" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                @csrf

                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>

                <div class="mb-3">
                    <label for="file" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Pilih File Excel
                    </label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls" class="hidden" required>
                </div>

                <p class="text-xs text-gray-500" id="fileName">Belum ada file dipilih</p>
                <p class="text-xs text-gray-400 mt-2">Maksimal 5MB, format .xlsx atau .xls</p>

                @error('file')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror

                <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                    Upload & Import Data
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('file').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Belum ada file dipilih';
        document.getElementById('fileName').textContent = fileName;
    });
</script>
@endsection
