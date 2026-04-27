<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class AnalisisWargaController extends Controller
{
    public function index()
    {
        // =========================
        // DATA PER KEPALA KELUARGA (KONSISTEN)
        // =========================

        // Ambil semua Kepala Keluarga
        $kepalaKeluarga = User::where('role', 'warga')
            ->where('status_keluarga', 'kepala_keluarga')
            ->get();

        $totalKK = $kepalaKeluarga->count();

        // Hitung statistik per KK
        $totalKKMiskin = 0;
        $totalKKRentan = 0;
        $totalKKMampu = 0;
        $totalKKTidakBekerja = 0;
        $totalKKPendapatanRendah = 0;
        $pendidikanKK = [];

        foreach ($kepalaKeluarga as $kk) {
            // Hitung total pendapatan 1 KK
            $totalPendapatanKK = User::where('role', 'warga')
                ->where('kepala_keluarga_nik', $kk->nik)
                ->sum('pendapatan_bulanan');

            // Kategori sosial KK (berdasarkan total pendapatan)
            if ($totalPendapatanKK < 1000000) {
                $totalKKMiskin++;
            } elseif ($totalPendapatanKK < 2000000) {
                $totalKKRentan++;
            } else {
                $totalKKMampu++;
            }

            // Status pekerjaan KK
            if ($kk->status_pekerjaan == 'tidak_bekerja') {
                $totalKKTidakBekerja++;
            }

            // Pendapatan rendah
            if ($totalPendapatanKK < 2000000) {
                $totalKKPendapatanRendah++;
            }

            // Pendidikan KK (untuk dominan)
            if ($kk->pendidikan_terakhir) {
                $pendidikanKK[$kk->pendidikan_terakhir] = ($pendidikanKK[$kk->pendidikan_terakhir] ?? 0) + 1;
            }
        }

        $pendidikanDominan = !empty($pendidikanKK) ? array_keys($pendidikanKK, max($pendidikanKK))[0] : '-';

        // =========================
        // STATISTIK UMUM (UNTUK CHART)
        // =========================
        $totalWarga     = User::where('role', 'warga')->count();
        $totalLakiLaki  = User::where('role', 'warga')->where('jenis_kelamin', 'Laki-laki')->count();
        $totalPerempuan = User::where('role', 'warga')->where('jenis_kelamin', 'Perempuan')->count();

        $pekerjaanData = [
            'bekerja'       => User::where('role', 'warga')->where('status_pekerjaan', 'bekerja')->count(),
            'tidak_bekerja' => User::where('role', 'warga')->where('status_pekerjaan', 'tidak_bekerja')->count(),
            'mahasiswa'     => User::where('role', 'warga')->where('status_pekerjaan', 'mahasiswa')->count(),
            'pensiun'       => User::where('role', 'warga')->where('status_pekerjaan', 'pensiun')->count(),
            'belum_diisi'   => User::where('role', 'warga')->whereNull('status_pekerjaan')->count(),
        ];

        $pendidikanData = [
            'sd'           => User::where('role', 'warga')->where('pendidikan_terakhir', 'SD')->count(),
            'smp'          => User::where('role', 'warga')->where('pendidikan_terakhir', 'SMP')->count(),
            'sma'          => User::where('role', 'warga')->where('pendidikan_terakhir', 'SMA')->count(),
            'd3'           => User::where('role', 'warga')->where('pendidikan_terakhir', 'D3')->count(),
            's1'           => User::where('role', 'warga')->where('pendidikan_terakhir', 'S1')->count(),
            's2'           => User::where('role', 'warga')->where('pendidikan_terakhir', 'S2')->count(),
            'belum_diisi'  => User::where('role', 'warga')->whereNull('pendidikan_terakhir')->count(),
        ];

        $sosialData = [
            'miskin'        => User::where('role', 'warga')->where('kategori_sosial', 'miskin')->count(),
            'rentan'        => User::where('role', 'warga')->where('kategori_sosial', 'rentan')->count(),
            'mampu'         => User::where('role', 'warga')->where('kategori_sosial', 'mampu')->count(),
            'belum_diisi'   => User::where('role', 'warga')->whereNull('kategori_sosial')->count(),
        ];

        $penerimaBantuan = User::where('role', 'warga')
            ->where('is_penerima_bantuan', true)
            ->count();

        // =========================
        // PRIORITAS BANTUAN PER KEPALA KELUARGA
        // =========================
        $prioritasBantuan = User::where('role', 'warga')
            ->where('status_keluarga', 'kepala_keluarga')
            ->selectRaw("
                users.*,
                (
                    SELECT COALESCE(SUM(pendapatan_bulanan), 0)
                    FROM users as anggota
                    WHERE anggota.kepala_keluarga_nik = users.nik
                ) as total_pendapatan_kk,
                (
                    SELECT COUNT(*)
                    FROM users as anggota
                    WHERE anggota.kepala_keluarga_nik = users.nik
                ) as total_anggota_kk
            ")
            ->having('total_pendapatan_kk', '<', 2000000)
            ->orderBy('total_pendapatan_kk', 'asc')
            ->limit(10)
            ->get();

        // =========================
        // DATA PENDAPATAN PER KATEGORI
        // =========================
        $pendapatanPerKategori = [
            'miskin' => User::where('role', 'warga')->where('kategori_sosial', 'miskin')->avg('pendapatan_bulanan') ?? 0,
            'rentan' => User::where('role', 'warga')->where('kategori_sosial', 'rentan')->avg('pendapatan_bulanan') ?? 0,
            'mampu'  => User::where('role', 'warga')->where('kategori_sosial', 'mampu')->avg('pendapatan_bulanan') ?? 0,
        ];

        // =========================
        // TREND PEKERJAAN 6 BULAN
        // =========================
        $trendPekerjaan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $trendPekerjaan['labels'][] = $bulan->format('M Y');
            $trendPekerjaan['bekerja'][] = User::where('role', 'warga')
                ->where('status_pekerjaan', 'bekerja')
                ->whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();
            $trendPekerjaan['tidak_bekerja'][] = User::where('role', 'warga')
                ->where('status_pekerjaan', 'tidak_bekerja')
                ->whereMonth('created_at', $bulan->month)
                ->whereYear('created_at', $bulan->year)
                ->count();
        }

        return view('analisis.warga', compact(
            'totalKK',
            'totalKKMiskin',
            'totalKKRentan',
            'totalKKMampu',
            'totalKKTidakBekerja',
            'pendidikanDominan',
            'totalWarga',
            'totalLakiLaki',
            'totalPerempuan',
            'pekerjaanData',
            'pendidikanData',
            'sosialData',
            'penerimaBantuan',
            'prioritasBantuan',
            'pendapatanPerKategori',
            'trendPekerjaan'
        ));
    }

    public function export()
    {
        $warga = User::where('role', 'warga')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'NIK', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
            'Pekerjaan', 'Status Pekerjaan', 'Pendidikan Terakhir', 'Alamat', 'RT/RW',
            'Kode Pos', 'Kategori Sosial', 'Pendapatan Bulanan', 'Jumlah Tanggungan', 'No. Telepon'
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        $row = 2;
        foreach ($warga as $w) {
            $sheet->setCellValue('A' . $row, $w->nik);
            $sheet->setCellValue('B' . $row, $w->name);
            $sheet->setCellValue('C' . $row, $w->jenis_kelamin);
            $sheet->setCellValue('D' . $row, $w->tempat_lahir);
            $sheet->setCellValue('E' . $row, optional($w->tanggal_lahir)->format('Y-m-d'));
            $sheet->setCellValue('F' . $row, $w->pekerjaan);
            $sheet->setCellValue('G' . $row, $w->status_pekerjaan);
            $sheet->setCellValue('H' . $row, $w->pendidikan_terakhir);
            $sheet->setCellValue('I' . $row, $w->alamat);
            $sheet->setCellValue('J' . $row, $w->rt_rw);
            $sheet->setCellValue('K' . $row, $w->kode_pos);
            $sheet->setCellValue('L' . $row, $w->kategori_sosial);
            $sheet->setCellValue('M' . $row, $w->pendapatan_bulanan);
            $sheet->setCellValue('N' . $row, $w->jumlah_tanggungan);
            $sheet->setCellValue('O' . $row, $w->phone);
            $row++;
        }

        foreach (range('A', 'O') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="data_warga_' . date('Y-m-d') . '.xlsx"');
        $writer->save('php://output');
        exit;
    }
}
