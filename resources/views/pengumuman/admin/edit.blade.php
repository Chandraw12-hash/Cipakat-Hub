@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">Edit Pengumuman</h1>
            <p class="text-gray-500 text-sm mt-1">Edit data pengumuman</p>
        </div>

        <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <input type="text" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                <textarea name="isi" rows="8" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ old('isi', $pengumuman->isi) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="biasa" {{ old('jenis', $pengumuman->jenis) == 'biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="penting" {{ old('jenis', $pengumuman->jenis) == 'penting' ? 'selected' : '' }}>Penting</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="draft" {{ old('status', $pengumuman->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $pengumuman->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                @if($pengumuman->gambar)
                    <div class="mb-2">
                        <img src="{{ Storage::url($pengumuman->gambar) }}" class="w-32 h-32 object-cover rounded-lg">
                    </div>
                @endif
                <input type="file" name="gambar" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Update
                </button>
                <a href="{{ route('pengumuman.admin') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
