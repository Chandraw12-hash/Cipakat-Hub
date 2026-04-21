@extends('layouts.app')

@section('title', 'Tambah Pengumuman')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">Tambah Pengumuman</h1>
            <p class="text-gray-500 text-sm mt-1">Buat pengumuman baru untuk warga desa</p>
        </div>

        <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5" id="pengumumanForm">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman <span class="text-red-500">*</span></label>
                <textarea name="isi" rows="8" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('isi') }}</textarea>
                @error('isi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="biasa" {{ old('jenis') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="penting" {{ old('jenis') == 'penting' ? 'selected' : '' }}>Penting</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                    <select name="target" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="publik" {{ old('target') == 'publik' ? 'selected' : '' }}>Publik (Tampil di Landing Page)</option>
                        <option value="internal" {{ old('target') == 'internal' ? 'selected' : '' }}>Internal (Hanya Dashboard)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Publik = semua orang bisa lihat, Internal = hanya warga login</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar (Opsional)</label>
                <input type="file" name="gambar" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB</p>
                @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Simpan
                </button>
                <a href="{{ route('pengumuman.admin') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Notifikasi error dari session
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

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: '{!! implode('<br>', $errors->all()) !!}',
            confirmButtonColor: '#d33'
        });
    @endif
</script>
@endsection
