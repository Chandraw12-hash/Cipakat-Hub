<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');

        // Cek apakah tabel layanan_surats ada
        $totalSurat = 0;
        if (Schema::hasTable('layanan_surats')) {
            $totalSurat = DB::table('layanan_surats')->count();
        }

        // Cek apakah tabel bookings ada
        $totalBooking = 0;
        if (Schema::hasTable('bookings')) {
            $totalBooking = DB::table('bookings')->count();
        }

        $totalUser = User::count();

        return view('laporan.index', compact(
            'totalPemasukan', 'totalPengeluaran',
            'totalSurat', 'totalBooking', 'totalUser'
        ));
    }

    public function keuangan(Request $request)
    {
        $query = Keuangan::with('user');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $keuangans = $query->latest()->get();
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('laporan.keuangan', compact('keuangans', 'totalPemasukan', 'totalPengeluaran', 'saldo'));
    }

    public function surat(Request $request)
    {
        // Ambil data dari tabel layanan_surats
        if (Schema::hasTable('layanan_surats')) {
            $surats = DB::table('layanan_surats')
                ->leftJoin('users', 'layanan_surats.user_id', '=', 'users.id')
                ->select('layanan_surats.*', 'users.name as user_name')
                ->orderBy('layanan_surats.created_at', 'desc')
                ->get();
        } else {
            $surats = collect();
        }

        return view('laporan.surat', compact('surats'));
    }

    public function booking(Request $request)
    {
        // Ambil data dari tabel bookings
        if (Schema::hasTable('bookings')) {
            $bookings = DB::table('bookings')
                ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
                ->select('bookings.*', 'users.name as user_name')
                ->orderBy('bookings.created_at', 'desc')
                ->get();
        } else {
            $bookings = collect();
        }

        return view('laporan.booking', compact('bookings'));
    }

    public function exportKeuanganPdf(Request $request)
    {
        $query = Keuangan::with('user');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $keuangans = $query->latest()->get();
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $pdf = Pdf::loadView('laporan.pdf.keuangan', compact(
            'keuangans', 'totalPemasukan', 'totalPengeluaran', 'saldo'
        ));

        return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }

    public function exportSuratPdf(Request $request)
    {
        if (Schema::hasTable('layanan_surats')) {
            $surats = DB::table('layanan_surats')
                ->leftJoin('users', 'layanan_surats.user_id', '=', 'users.id')
                ->select('layanan_surats.*', 'users.name as user_name')
                ->orderBy('layanan_surats.created_at', 'desc')
                ->get();
        } else {
            $surats = collect();
        }

        $pdf = Pdf::loadView('laporan.pdf.surat', compact('surats'));
        return $pdf->download('laporan-surat-' . date('Y-m-d') . '.pdf');
    }

    public function exportBookingPdf(Request $request)
    {
        if (Schema::hasTable('bookings')) {
            $bookings = DB::table('bookings')
                ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
                ->select('bookings.*', 'users.name as user_name')
                ->orderBy('bookings.created_at', 'desc')
                ->get();
        } else {
            $bookings = collect();
        }

        $pdf = Pdf::loadView('laporan.pdf.booking', compact('bookings'));
        return $pdf->download('laporan-booking-' . date('Y-m-d') . '.pdf');
    }
}
