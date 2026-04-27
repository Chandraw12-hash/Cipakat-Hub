@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Tambah User Baru</h1>
                        <p class="text-sm text-gray-500">Lengkapi data profil warga / petugas / admin</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-6">

                    <!-- ===== FOTO PROFIL ===== -->
                    <div class="border-b border-gray-100 pb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <h3 class="font-medium text-gray-800">Foto Profil</h3>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 rounded-full bg-gray-100 border-2 border-gray-200 flex items-center justify-center overflow-hidden" id="photoPreview">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Upload Foto</label>
                                <input type="file" name="photo" id="photoInput" accept="image/*"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-400 mt-1">Format JPG/PNG, maks 2MB</p>
                                @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ===== INFORMASI AKUN ===== -->
                    <div class="border-b border-gray-100 pb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="font-medium text-gray-800">Informasi Akun</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter</p>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Role <span class="text-red-500">*</span></label>
                                <select name="role" required class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                    <option value="warga" {{ old('role') == 'warga' ? 'selected' : '' }}>Warga</option>
                                    <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ===== PEMBERITAHUAN OTOMATIS ===== -->
                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-blue-700 font-medium">💡 Fitur Otomatis</p>
                        </div>
                        <p class="text-xs text-blue-600 mt-1 ml-7">• Kategori Sosial akan otomatis terisi berdasarkan pendapatan</p>
                        <p class="text-xs text-blue-600 ml-7">• Status Pekerjaan akan otomatis terisi berdasarkan pekerjaan</p>
                    </div>

                    <!-- ===== DATA DIRI ===== -->
                    <div class="border-b border-gray-100 pb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <h3 class="font-medium text-gray-800">Data Diri</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">NIK</label>
                                <input type="text" name="nik" value="{{ old('nik') }}"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                @error('nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">No. Telepon / WA</label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                @error('tempat_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                @error('tanggal_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Pekerjaan</label>
                                <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                @error('pekerjaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Status Pekerjaan</label>
                                <select name="status_pekerjaan" id="status_pekerjaan" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                    <option value="">Pilih</option>
                                    <option value="bekerja" {{ old('status_pekerjaan') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                                    <option value="tidak_bekerja" {{ old('status_pekerjaan') == 'tidak_bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                    <option value="mahasiswa" {{ old('status_pekerjaan') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                    <option value="pensiun" {{ old('status_pekerjaan') == 'pensiun' ? 'selected' : '' }}>Pensiun</option>
                                </select>
                                @error('status_pekerjaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                    <option value="">Pilih</option>
                                    <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ old('pendidikan_terakhir') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                                </select>
                                @error('pendidikan_terakhir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ===== DATA SOSIAL EKONOMI (DENGAN OTOMATISASI) ===== -->
                    <div class="border-b border-gray-100 pb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="font-medium text-gray-800">Sosial Ekonomi <span class="text-xs text-gray-400 font-normal ml-2">(Otomatis berdasarkan pendapatan)</span></h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Pendapatan Bulanan</label>
                                <input type="number" name="pendapatan_bulanan" id="pendapatan_bulanan" value="{{ old('pendapatan_bulanan') }}"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2" placeholder="Masukkan nominal">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ada penghasilan</p>
                                @error('pendapatan_bulanan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Kategori Sosial <span class="text-xs text-blue-500 font-normal">(Otomatis)</span></label>
                                <select name="kategori_sosial" id="kategori_sosial" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50">
                                    <option value="">Akan terisi otomatis</option>
                                    <option value="rentan" {{ old('kategori_sosial') == 'rentan' ? 'selected' : '' }}>Rentan</option>
                                    <option value="mampu" {{ old('kategori_sosial') == 'mampu' ? 'selected' : '' }}>Mampu</option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">↓ Isi pendapatan dulu, akan terisi otomatis</p>
                                @error('kategori_sosial') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Jumlah Tanggungan</label>
                                <input type="number" name="jumlah_tanggungan" value="{{ old('jumlah_tanggungan') }}"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                @error('jumlah_tanggungan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Status Rumah</label>
                                <select name="status_rumah" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                    <option value="">Pilih</option>
                                    <option value="milik_sendiri" {{ old('status_rumah') == 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                                    <option value="kontrak" {{ old('status_rumah') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                                    <option value="keluarga" {{ old('status_rumah') == 'keluarga' ? 'selected' : '' }}>Keluarga</option>
                                </select>
                                @error('status_rumah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Penerima Bantuan</label>
                                <select name="is_penerima_bantuan" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                    <option value="0" {{ old('is_penerima_bantuan') == '0' ? 'selected' : '' }}>Tidak</option>
                                    <option value="1" {{ old('is_penerima_bantuan') == '1' ? 'selected' : '' }}>Ya</option>
                                </select>
                                @error('is_penerima_bantuan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- ===== ALAMAT ===== -->
                    <div class="border-b border-gray-100 pb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h3 class="font-medium text-gray-800">Alamat</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Alamat Lengkap</label>
                                <textarea name="alamat" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">{{ old('alamat') }}</textarea>
                                @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 mb-1 block">RT / RW</label>
                                    <input type="text" name="rt_rw" value="{{ old('rt_rw') }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-700 mb-1 block">Kode Pos</label>
                                    <input type="text" name="kode_pos" value="{{ old('kode_pos') }}"
                                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== DATA KELUARGA ===== -->
                    <div class="border-b border-gray-100 pb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <h3 class="font-medium text-gray-800">Data Keluarga</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">NIK Kepala Keluarga</label>
                                <input type="text" name="kepala_keluarga_nik" value="{{ old('kepala_keluarga_nik') }}"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                @error('kepala_keluarga_nik') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-1 block">Status Keluarga</label>
                                <select name="status_keluarga" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2">
                                    <option value="kepala_keluarga" {{ old('status_keluarga') == 'kepala_keluarga' ? 'selected' : '' }}>Kepala Keluarga</option>
                                    <option value="istri" {{ old('status_keluarga') == 'istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="anak" {{ old('status_keluarga') == 'anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="lainnya" {{ old('status_keluarga') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('status_keluarga') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                            Simpan User
                        </button>
                        <a href="{{ route('users.index') }}" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Preview foto
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    photoPreview.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                }
                reader.readAsDataURL(file);
            } else {
                photoPreview.innerHTML = `<svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>`;
            }
        });
    }

    // OTOMATIS KATEGORI SOSIAL BERDASARKAN PENDAPATAN
    const pendapatanInput = document.getElementById('pendapatan_bulanan');
    const kategoriSelect = document.getElementById('kategori_sosial');
    const pekerjaanInput = document.getElementById('pekerjaan');
    const statusPekerjaanSelect = document.getElementById('status_pekerjaan');
    const pendidikanSelect = document.getElementById('pendidikan_terakhir');

    function updateKategoriOtomatis() {
        const pendapatan = parseFloat(pendapatanInput.value);

        if (isNaN(pendapatan) || pendapatan === 0) {
            kategoriSelect.value = '';
            return;
        }

        if (pendapatan < 2000000) {
            kategoriSelect.value = 'rentan';
        } else {
            kategoriSelect.value = 'mampu';
        }
    }

    function updateStatusPekerjaanOtomatis() {
        const pekerjaan = pekerjaanInput.value.toLowerCase();

        if (pekerjaan.includes('pns') || pekerjaan.includes('bumn') || pekerjaan.includes('guru') ||
            pekerjaan.includes('dokter') || pekerjaan.includes('polisi') || pekerjaan.includes('tni') ||
            pekerjaan.includes('karyawan') || pekerjaan.includes('wiraswasta')) {
            statusPekerjaanSelect.value = 'bekerja';
        } else if (pekerjaan.includes('petani') || pekerjaan.includes('buruh') || pekerjaan.includes('nelayan') || pekerjaan.includes('tukang')) {
            statusPekerjaanSelect.value = 'bekerja';
        } else if (pekerjaan.includes('mahasiswa') || pekerjaan.includes('pelajar')) {
            statusPekerjaanSelect.value = 'mahasiswa';
        } else if (pekerjaan.includes('ibu rumah tangga') || pekerjaan.includes('irt')) {
            statusPekerjaanSelect.value = 'tidak_bekerja';
        } else if (pekerjaan && pekerjaan !== '') {
            if (statusPekerjaanSelect.value === '') {
                statusPekerjaanSelect.value = 'bekerja';
            }
        }
    }

    function updatePendidikanOtomatis() {
        const pekerjaan = pekerjaanInput.value.toLowerCase();

        if (pekerjaan.includes('pns') || pekerjaan.includes('guru') || pekerjaan.includes('dokter') || pekerjaan.includes('dosen')) {
            if (pendidikanSelect.value === '') pendidikanSelect.value = 'S1';
        } else if (pekerjaan.includes('karyawan') || pekerjaan.includes('polisi') || pekerjaan.includes('tni')) {
            if (pendidikanSelect.value === '') pendidikanSelect.value = 'SMA';
        } else if (pekerjaan.includes('mahasiswa')) {
            if (pendidikanSelect.value === '') pendidikanSelect.value = 'SMA';
        } else if (pekerjaan.includes('petani') || pekerjaan.includes('buruh') || pekerjaan.includes('nelayan')) {
            if (pendidikanSelect.value === '') pendidikanSelect.value = 'SD';
        }
    }

    // Event listeners
    if (pendapatanInput) {
        pendapatanInput.addEventListener('input', updateKategoriOtomatis);
        pendapatanInput.addEventListener('blur', function() {
            if (pendapatanInput.value && !kategoriSelect.value) {
                updateKategoriOtomatis();
            }
        });
    }

    if (pekerjaanInput) {
        pekerjaanInput.addEventListener('change', function() {
            updateStatusPekerjaanOtomatis();
            updatePendidikanOtomatis();
        });
        pekerjaanInput.addEventListener('keyup', function() {
            updateStatusPekerjaanOtomatis();
            updatePendidikanOtomatis();
        });
    }

    // Validasi NIK sebelum submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nikInput = document.querySelector('input[name="nik"]');
            if (nikInput && nikInput.value) {
                const nik = nikInput.value;
                if (nik.length !== 16 || !/^\d+$/.test(nik)) {
                    e.preventDefault();
                    alert('⚠️ NIK harus berupa 16 digit angka!');
                    return false;
                }
            }
        });
    }
</script>
@endsection
