<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LayananSurat;
use App\Models\ProdukUmkm;
use App\Models\Keuangan;
use App\Models\Booking;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk semua role
        $totalWarga = User::count();
        $totalSurat = LayananSurat::count();
        $totalProduk = ProdukUmkm::where('status', 'aktif')->count();

        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldoKas = $totalPemasukan - $totalPengeluaran;

        $pemasukanBulanIni = Keuangan::where('jenis', 'pemasukan')
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');

        $wargaBaru = User::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        $suratPending = LayananSurat::where('status', 'pending')->count();
        $bookingPending = Booking::where('status', 'pending')->count();
        $produkNonaktif = ProdukUmkm::where('status', 'nonaktif')->count();

        // Data untuk warga
        $suratSaya = LayananSurat::where('user_id', Auth::id())->count();
        $bookingSaya = Booking::where('user_id', Auth::id())->count();

        // Data untuk chart (6 bulan terakhir)
        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $chartLabels[] = $bulan->translatedFormat('M Y');

            $pemasukan = Keuangan::where('jenis', 'pemasukan')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');

            $pengeluaran = Keuangan::where('jenis', 'pengeluaran')
                ->whereMonth('tanggal', $bulan->month)
                ->whereYear('tanggal', $bulan->year)
                ->sum('jumlah');

            $chartPemasukan[] = $pemasukan;
            $chartPengeluaran[] = $pengeluaran;
        }

        // PENGUMUMAN TERBARU (untuk dashboard)
        $pengumuman = Pengumuman::where('status', 'published')
            ->latest('published_at')
            ->limit(5)
            ->get();

        // Aktivitas terbaru (gabungan dari berbagai tabel)
        $aktivitasTerbaru = collect();

        // Ambil pengajuan surat terbaru
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

        // Ambil booking terbaru
        $bookingTerbaru = Booking::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'user' => $item->user->name,
                    'aksi' => 'booking ' . $item->item,
                    'waktu' => $item->created_at->diffForHumans(),
                    'link' => route('booking.show', $item->id)
                ];
            });

        // Ambil transaksi keuangan terbaru
        $transaksiTerbaru = Keuangan::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'user' => $item->user->name,
                    'aksi' => 'menambahkan transaksi ' . $item->jenis . ' ' . $item->kategori,
                    'waktu' => $item->created_at->diffForHumans(),
                    'link' => route('keuangan.edit', $item->id)
                ];
            });

        // Gabungkan semua aktivitas
        $aktivitasTerbaru = $suratTerbaru
            ->concat($bookingTerbaru)
            ->concat($transaksiTerbaru)
            ->sortByDesc('waktu')
            ->take(10);

        return view('dashboard.index', compact(
            'totalWarga',
            'totalSurat',
            'totalProduk',
            'saldoKas',
            'pemasukanBulanIni',
            'wargaBaru',
            'suratPending',
            'bookingPending',
            'produkNonaktif',
            'aktivitasTerbaru',
            'chartLabels',
            'chartPemasukan',
            'chartPengeluaran',
            'suratSaya',
            'bookingSaya',
            'pengumuman'
        ));
    }
}
