@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">Edit Transaksi</h1>
            <p class="text-gray-500 text-sm mt-1">Edit data transaksi</p>
        </div>

        <form action="{{ route('keuangan.update', $keuangan->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                <select name="jenis" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="pemasukan" {{ old('jenis', $keuangan->jenis) == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ old('jenis', $keuangan->jenis) == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="kategori" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Kategori</option>
                    <optgroup label="Pemasukan">
                        <option value="Iuran Warga" {{ old('kategori', $keuangan->kategori) == 'Iuran Warga' ? 'selected' : '' }}>Iuran Warga</option>
                        <option value="Hasil Usaha" {{ old('kategori', $keuangan->kategori) == 'Hasil Usaha' ? 'selected' : '' }}>Hasil Usaha</option>
                        <option value="Donasi" {{ old('kategori', $keuangan->kategori) == 'Donasi' ? 'selected' : '' }}>Donasi</option>
                        <option value="Bantuan Desa" {{ old('kategori', $keuangan->kategori) == 'Bantuan Desa' ? 'selected' : '' }}>Bantuan Desa</option>
                    </optgroup>
                    <optgroup label="Pengeluaran">
                        <option value="Operasional" {{ old('kategori', $keuangan->kategori) == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                        <option value="Pembangunan" {{ old('kategori', $keuangan->kategori) == 'Pembangunan' ? 'selected' : '' }}>Pembangunan</option>
                        <option value="Gaji" {{ old('kategori', $keuangan->kategori) == 'Gaji' ? 'selected' : '' }}>Gaji</option>
                        <option value="Peralatan" {{ old('kategori', $keuangan->kategori) == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
                    </optgroup>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $keuangan->deskripsi) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', $keuangan->jumlah) }}" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $keuangan->tanggal->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Update
                </button>
                <a href="{{ route('keuangan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
