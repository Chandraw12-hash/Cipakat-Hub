<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LayananSurat;
use App\Models\ProdukUmkm;
use App\Models\Keuangan;
use App\Models\Booking;
use App\Models\Pengumuman;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== DATA BUMDes ====================
        $namaDesa = Setting::get('desa_nama', 'Cipakat');
        $alamatDesa = Setting::get('kontak_alamat', 'Desa Cipakat');
        $ahl = Setting::get('ahl', '123-456-789-000');
        $kepalaDesa = Setting::get('kepala_desa', 'Kepala Desa');
        $direktur = Setting::get('direktur', 'Direktur BUMDes');
        $sekretaris = Setting::get('sekretaris', 'Sekretaris BUMDes');

        // ==================== DATA PENGURUS DARI DATABASE USERS ====================
        $pengurusList = User::whereIn('role', ['admin', 'petugas'])
            ->orderByRaw("FIELD(role, 'admin', 'petugas')")
            ->get()
            ->map(function($user) {
                // Tentukan warna berdasarkan role
                $warna = match($user->role) {
                    'admin' => 'blue',
                    'petugas' => 'green',
                    default => 'gray'
                };

                // Tentukan singkatan
                $singkatan = match($user->role) {
                    'admin' => 'ADM',
                    'petugas' => 'PTG',
                    default => 'STF'
                };

                // Tentukan jabatan
                $jabatan = match($user->role) {
                    'admin' => 'Administrator BUMDes',
                    'petugas' => 'Petugas BUMDes',
                    default => 'Staff BUMDes'
                };

                return (object) [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'warna' => $warna,
                    'singkatan' => $singkatan,
                    'jabatan' => $jabatan,
                    'photo' => $user->photo,
                    'phone' => $user->phone,
                    'nik' => $user->nik,
                    'alamat' => $user->alamat,
                ];
            });

        // ==================== DATA KEUANGAN ====================
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldoKas = $totalPemasukan - $totalPengeluaran;

        // Total Aset (Aktiva) = semua pemasukan + saldo kas
        $totalAset = $totalPemasukan;

        // Total Pasiva = semua pengeluaran + kewajiban
        $totalPasiva = $totalPengeluaran;

        // Total Modal = modal awal + laba ditahan
        $modalAwal = Setting::get('modal_awal', 0);
        $labaDitahan = Keuangan::where('jenis', 'pemasukan')->where('kategori', 'laba')->sum('jumlah');
        $totalModal = $modalAwal + $labaDitahan;

        // Laba/Rugi tahun ini
        $labaRugiTahunIni = Keuangan::whereYear('tanggal', date('Y'))
            ->selectRaw('SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE -jumlah END) as laba')
            ->first()->laba ?? 0;

        // Growth (perubahan dari bulan lalu)
        $bulanLalu = Keuangan::whereMonth('tanggal', date('m', strtotime('-1 month')))
            ->whereYear('tanggal', date('Y', strtotime('-1 month')))
            ->selectRaw('SUM(CASE WHEN jenis = "pemasukan" THEN jumlah ELSE -jumlah END) as laba')
            ->first()->laba ?? 0;

        $labaRugiGrowth = $labaRugiTahunIni - $bulanLalu;
        $asetGrowth = Keuangan::whereMonth('tanggal', date('m'))->sum('jumlah') -
                      Keuangan::whereMonth('tanggal', date('m', strtotime('-1 month')))->sum('jumlah');

        // ==================== DATA STATISTIK ====================
        $totalWarga = User::where('role', 'warga')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalPetugas = User::where('role', 'petugas')->count();

        $totalSurat = LayananSurat::count();
        $suratPending = LayananSurat::where('status', 'pending')->count();
        $suratDiproses = LayananSurat::where('status', 'diproses')->count();
        $suratSelesai = LayananSurat::where('status', 'selesai')->count();
        $suratDitolak = LayananSurat::where('status', 'ditolak')->count();

        $totalProduk = ProdukUmkm::count();
        $produkAktif = ProdukUmkm::where('status', 'aktif')->count();
        $produkNonaktif = ProdukUmkm::where('status', 'nonaktif')->count();

        $totalBooking = Booking::count();
        $bookingPending = Booking::where('status', 'pending')->count();
        $bookingConfirmed = Booking::where('status', 'confirmed')->count();
        $bookingSelesai = Booking::where('status', 'selesai')->count();

        $pemasukanBulanIni = Keuangan::where('jenis', 'pemasukan')
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');

        $wargaBaru = User::where('role', 'warga')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        // ==================== DATA UNTUK WARGA ====================
        $suratSaya = LayananSurat::where('user_id', Auth::id())->count();
        $bookingSaya = Booking::where('user_id', Auth::id())->count();

        // ==================== DATA UNTUK GRAFIK ====================
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];
        $chartPendapatan = [];
        $chartBiaya = [];
        $chartLaba = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $chartLabels[] = $bulan->translatedFormat('M Y');

            // Untuk grafik keuangan (pemasukan & pengeluaran)
            $pemasukan = Keuangan::where('jenis', 'pemasukan')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
            $chartPemasukan[] = $pemasukan;

            $pengeluaran = Keuangan::where('jenis', 'pengeluaran')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
            $chartPengeluaran[] = $pengeluaran;

            // Untuk grafik kinerja (pendapatan, biaya, laba)
            $pendapatan = Keuangan::where('jenis', 'pemasukan')
                ->whereIn('kategori', ['pendapatan', 'jasa', 'sewa'])
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
            $chartPendapatan[] = $pendapatan;

            $biaya = Keuangan::where('jenis', 'pengeluaran')
                ->whereIn('kategori', ['operasional', 'gaji', 'perawatan'])
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');
            $chartBiaya[] = $biaya;

            $laba = $pendapatan - $biaya;
            $chartLaba[] = $laba;
        }

        // ==================== PENGUMUMAN TERBARU ====================
        $pengumuman = Pengumuman::where('status', 'published')
            ->latest('published_at')
            ->limit(5)
            ->get();

        // ==================== AKTIVITAS TERBARU ====================
        $aktivitasTerbaru = collect();

        $suratTerbaru = LayananSurat::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'user' => $item->user->name,
                    'aksi' => 'mengajukan surat ' . $item->jenis_surat,
                    'waktu' => $item->created_at->diffForHumans(),
                    'link' => route('layanan.show', $item->id)
                ];
            });

        $bookingTerbaru = Booking::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'user' => $item->user->name,
                    'aksi' => 'booking ' . ($item->item ?? 'fasilitas'),
                    'waktu' => $item->created_at->diffForHumans(),
                    'link' => route('booking.show', $item->id)
                ];
            });

        $transaksiTerbaru = Keuangan::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'user' => $item->user->name,
                    'aksi' => 'menambahkan transaksi ' . $item->jenis . ' ' . ($item->kategori ?? ''),
                    'waktu' => $item->created_at->diffForHumans(),
                    'link' => '#'
                ];
            });

        $aktivitasTerbaru = $suratTerbaru
            ->concat($bookingTerbaru)
            ->concat($transaksiTerbaru)
            ->sortByDesc('waktu')
            ->take(10);

        // ==================== VIEW ====================
        return view('dashboard.index', compact(
            // Data BUMDes
            'namaDesa',
            'alamatDesa',
            'ahl',
            'kepalaDesa',
            'direktur',
            'sekretaris',

            // Data Pengurus dari database users
            'pengurusList',

            // Data Keuangan (untuk kartu utama)
            'totalAset',
            'totalPasiva',
            'totalModal',
            'labaRugiTahunIni',
            'asetGrowth',
            'labaRugiGrowth',
            'saldoKas',

            // Data Statistik
            'totalWarga',
            'totalAdmin',
            'totalPetugas',
            'totalSurat',
            'suratPending',
            'suratDiproses',
            'suratSelesai',
            'suratDitolak',
            'totalProduk',
            'produkAktif',
            'produkNonaktif',
            'totalBooking',
            'bookingPending',
            'bookingConfirmed',
            'bookingSelesai',
            'pemasukanBulanIni',
            'wargaBaru',

            // Data untuk warga
            'suratSaya',
            'bookingSaya',

            // Data Grafik
            'chartLabels',
            'chartPemasukan',
            'chartPengeluaran',
            'chartPendapatan',
            'chartBiaya',
            'chartLaba',

            // Data Lainnya
            'pengumuman',
            'aktivitasTerbaru'
        ));
    }
}
