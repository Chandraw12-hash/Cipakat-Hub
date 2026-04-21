@extends('layouts.app')

@section('title', 'Booking Fasilitas')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h1 class="text-lg font-semibold text-gray-800">Booking Fasilitas Desa</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi form di bawah untuk booking fasilitas</p>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" class="p-6 space-y-4" id="bookingForm">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                <select name="item" id="item" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Fasilitas</option>
                    @foreach($fasilitas as $f)
                        <option value="{{ $f->nama }}"
                                data-kategori="{{ $f->kategori }}"
                                data-harga-per-jam="{{ $f->harga_per_jam }}"
                                data-harga-full-day="{{ $f->harga_full_day }}">
                            {{ $f->nama }} - Rp {{ number_format($f->harga_per_jam, 0, ',', '.') }}/jam
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="kategori" id="kategori" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Kategori</option>
                    <option value="olahraga">Olahraga</option>
                    <option value="aula">Aula</option>
                    <option value="peralatan">Peralatan</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking</label>
                <input type="date" name="tanggal_booking" id="tanggal_booking" required min="{{ date('Y-m-d') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Booking</label>
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input type="radio" name="jenis_booking" value="per_jam" id="jenis_per_jam" checked class="mr-2"> Per Jam
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="jenis_booking" value="full_day" id="jenis_full_day" class="mr-2"> Full Day
                    </label>
                </div>
            </div>

            <div id="jadwalContainer" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Jam</label>
                <div id="jadwalSlots" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                <input type="hidden" name="jam_mulai" id="jam_mulai">
                <input type="hidden" name="jam_selesai" id="jam_selesai">
            </div>

            <div id="manualJamContainer">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" name="jam_mulai_manual" id="jam_mulai_manual" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" name="jam_selesai_manual" id="jam_selesai_manual" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
            </div>

            <!-- Jumlah (hanya untuk peralatan) -->
            <div id="jumlahContainer" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" value="1" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <p class="text-xs text-gray-500 mt-1">Jumlah unit yang disewa</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Harga (Rp)</label>
                <input type="number" name="harga" id="harga" value="0" min="0" readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-blue-600 font-semibold">
                <p class="text-xs text-gray-500 mt-1">Harga otomatis dihitung berdasarkan durasi dan jumlah</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                    Ajukan Booking
                </button>
                <a href="{{ route('booking.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const itemSelect = document.getElementById('item');
    const kategoriSelect = document.getElementById('kategori');
    const tanggalInput = document.getElementById('tanggal_booking');
    const jadwalContainer = document.getElementById('jadwalContainer');
    const jadwalSlots = document.getElementById('jadwalSlots');
    const jamMulaiHidden = document.getElementById('jam_mulai');
    const jamSelesaiHidden = document.getElementById('jam_selesai');
    const manualJamContainer = document.getElementById('manualJamContainer');
    const jamMulaiManual = document.getElementById('jam_mulai_manual');
    const jamSelesaiManual = document.getElementById('jam_selesai_manual');
    const jenisPerJam = document.getElementById('jenis_per_jam');
    const jenisFullDay = document.getElementById('jenis_full_day');
    const hargaInput = document.getElementById('harga');
    const jumlahInput = document.getElementById('jumlah');
    const jumlahContainer = document.getElementById('jumlahContainer');
    let hargaPerJam = 0;
    let hargaFullDay = 0;

    function hitungTotalHarga() {
        let jumlah = 1;
        const kategori = kategoriSelect.value;

        // Tampilkan jumlah hanya untuk peralatan
        if (kategori === 'peralatan') {
            jumlahContainer.style.display = 'block';
            jumlah = parseInt(jumlahInput.value) || 1;
        } else {
            jumlahContainer.style.display = 'none';
            jumlah = 1;
            jumlahInput.value = 1;
        }

        if (jenisFullDay.checked) {
            hargaInput.value = hargaFullDay * jumlah;
            return;
        }

        let jamMulai = jamMulaiHidden.value;
        let jamSelesai = jamSelesaiHidden.value;

        if (!jamMulai || !jamSelesai) {
            jamMulai = jamMulaiManual.value;
            jamSelesai = jamSelesaiManual.value;
        }

        if (jamMulai && jamSelesai && hargaPerJam > 0) {
            const mulai = jamMulai.split(':');
            const selesai = jamSelesai.split(':');
            let durasi = parseInt(selesai[0]) - parseInt(mulai[0]);
            if (durasi <= 0) durasi = 1;
            const total = hargaPerJam * durasi * jumlah;
            hargaInput.value = total;
        } else if (hargaPerJam > 0) {
            hargaInput.value = hargaPerJam;
        } else {
            hargaInput.value = 0;
        }
    }

    function setHargaDasar() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        if (!selectedOption.value) {
            hargaPerJam = 0;
            hargaFullDay = 0;
            hargaInput.value = 0;
            return;
        }
        hargaPerJam = parseInt(selectedOption.dataset.hargaPerJam) || 0;
        hargaFullDay = parseInt(selectedOption.dataset.hargaFullDay) || 0;

        if (jenisFullDay.checked) {
            hargaInput.value = hargaFullDay;
        } else {
            hargaInput.value = hargaPerJam;
        }
    }

    async function loadJadwal() {
        const item = itemSelect.value;
        const tanggal = tanggalInput.value;

        if (!item || !tanggal) {
            jadwalContainer.style.display = 'none';
            manualJamContainer.style.display = 'block';
            return;
        }

        try {
            const response = await fetch(`/booking/jadwal?item=${encodeURIComponent(item)}&tanggal=${tanggal}`);
            const slots = await response.json();

            jadwalContainer.style.display = 'block';
            manualJamContainer.style.display = 'none';
            jadwalSlots.innerHTML = '';

            slots.forEach(slot => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `p-2 rounded-md text-sm font-medium transition ${
                    slot.status === 'tersedia'
                        ? 'bg-green-100 text-green-700 hover:bg-green-200'
                        : 'bg-red-100 text-red-500 cursor-not-allowed'
                }`;
                button.textContent = slot.jam;
                button.disabled = slot.status === 'terbooking';

                if (slot.status === 'tersedia') {
                    button.onclick = () => {
                        document.querySelectorAll('#jadwalSlots button').forEach(btn => {
                            btn.classList.remove('ring-2', 'ring-blue-500', 'bg-green-200');
                        });
                        button.classList.add('ring-2', 'ring-blue-500', 'bg-green-200');
                        const jamRange = slot.jam.split(' - ');
                        jamMulaiHidden.value = jamRange[0];
                        jamSelesaiHidden.value = jamRange[1];
                        jamMulaiManual.value = '';
                        jamSelesaiManual.value = '';
                        hitungTotalHarga();
                    };
                }
                jadwalSlots.appendChild(button);
            });
        } catch (error) {
            console.error('Error loading jadwal:', error);
        }
    }

    function toggleJenisBooking() {
        if (jenisFullDay.checked) {
            jadwalContainer.style.display = 'none';
            manualJamContainer.style.display = 'none';
            jamMulaiHidden.value = '08:00';
            jamSelesaiHidden.value = '20:00';
            hargaInput.value = hargaFullDay;
        } else {
            loadJadwal();
            hitungTotalHarga();
        }
    }

    itemSelect.addEventListener('change', () => {
        setHargaDasar();
        if (jenisPerJam.checked) loadJadwal();
        const selected = itemSelect.options[itemSelect.selectedIndex];
        if (selected.value) {
            kategoriSelect.value = selected.dataset.kategori || '';
        }
        hitungTotalHarga();
    });

    tanggalInput.addEventListener('change', () => { if (jenisPerJam.checked) loadJadwal(); });
    jenisPerJam.addEventListener('change', () => {
        toggleJenisBooking();
        hitungTotalHarga();
    });
    jenisFullDay.addEventListener('change', () => {
        toggleJenisBooking();
        hitungTotalHarga();
    });

    jamMulaiManual.addEventListener('change', hitungTotalHarga);
    jamSelesaiManual.addEventListener('change', hitungTotalHarga);
    jumlahInput.addEventListener('change', hitungTotalHarga);

    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        let jamMulai = jamMulaiHidden.value;
        let jamSelesai = jamSelesaiHidden.value;
        let jamMulaiManualVal = jamMulaiManual.value;
        let jamSelesaiManualVal = jamSelesaiManual.value;

        if (jenisFullDay.checked) return true;
        if (jamMulai && jamSelesai) return true;
        if (jamMulaiManualVal && jamSelesaiManualVal) {
            jamMulaiHidden.value = jamMulaiManualVal;
            jamSelesaiHidden.value = jamSelesaiManualVal;
            return true;
        }
        e.preventDefault();
        Swal.fire({ icon: 'warning', title: 'Perhatian!', text: 'Silakan pilih jam booking terlebih dahulu!', confirmButtonColor: '#3085d6' });
    });

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonColor: '#3085d6', timer: 3000 });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}', confirmButtonColor: '#d33' });
    @endif
    @if($errors->any())
        Swal.fire({ icon: 'error', title: 'Gagal!', html: '{!! implode('<br>', $errors->all()) !!}', confirmButtonColor: '#d33' });
    @endif
</script>
@endsection
