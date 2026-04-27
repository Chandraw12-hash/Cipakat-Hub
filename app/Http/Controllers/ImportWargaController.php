<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportWargaController extends Controller
{
    public function index()
    {
        return view('import.warga');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120'
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Ambil header dari baris pertama
            $headers = array_map('trim', $rows[0]);
            array_shift($rows); // hapus baris header

            $successCount = 0;
            $errorCount   = 0;
            $errors       = [];

            foreach ($rows as $index => $row) {
                // Mapping kolom berdasarkan nama header
                $data_row = [];
                foreach ($headers as $i => $header) {
                    $data_row[$header] = isset($row[$i]) ? trim($row[$i]) : null;
                }

                $nik  = $data_row['nik']  ?? '';
                $nama = $data_row['name'] ?? '';

                // Skip baris kosong
                if (empty($nik) && empty($nama)) continue;

                // Validasi wajib
                if (empty($nik) || empty($nama)) {
                    $errorCount++;
                    $errors[] = "Baris " . ($index + 2) . ": NIK dan Nama wajib diisi";
                    continue;
                }

                // Cek NIK duplikat
                if (User::where('nik', $nik)->exists()) {
                    $errorCount++;
                    $errors[] = "Baris " . ($index + 2) . ": NIK $nik sudah terdaftar";
                    continue;
                }

                // Cek email duplikat
                $email = !empty($data_row['email'])
                    ? $data_row['email']
                    : $nik . '@temp.cipakat.id';

                if (User::where('email', $email)->exists()) {
                    $email = $nik . '@temp.cipakat.id';
                }

                // Password
                $password = !empty($data_row['password'])
                    ? Hash::make($data_row['password'])
                    : Hash::make('password123');

                User::create([
                    'name'                 => $nama,
                    'email'                => $email,
                    'password'             => $password,
                    'role'                 => $data_row['role']                 ?? 'warga',
                    'nik'                  => $nik,
                    'phone'                => $data_row['phone']                ?? null,
                    'tempat_lahir'         => $data_row['tempat_lahir']         ?? null,
                    'tanggal_lahir'        => !empty($data_row['tanggal_lahir'])
                                                ? $data_row['tanggal_lahir']
                                                : null,
                    'jenis_kelamin'        => $data_row['jenis_kelamin']        ?? null,
                    'pekerjaan'            => $data_row['pekerjaan']            ?? null,
                    'status_pekerjaan'     => $data_row['status_pekerjaan']     ?? null,
                    'pendapatan_bulanan'   => is_numeric($data_row['pendapatan_bulanan'] ?? '')
                                                ? (int) $data_row['pendapatan_bulanan']
                                                : 0,
                    'pendidikan_terakhir'  => $data_row['pendidikan_terakhir']  ?? null,
                    'status_rumah'         => $data_row['status_rumah']         ?? null,
                    'kategori_sosial'      => $data_row['kategori_sosial']      ?? null,
                    'is_penerima_bantuan'  => isset($data_row['is_penerima_bantuan'])
                                                ? (int) $data_row['is_penerima_bantuan']
                                                : 0,
                    'jumlah_tanggungan'    => is_numeric($data_row['jumlah_tanggungan'] ?? '')
                                                ? (int) $data_row['jumlah_tanggungan']
                                                : 0,
                    'kepala_keluarga_nik'  => $data_row['kepala_keluarga_nik']  ?? null,
                    'alamat'               => $data_row['alamat']               ?? null,
                    'rt_rw'                => $data_row['rt_rw']                ?? null,
                    'kode_pos'             => $data_row['kode_pos']             ?? '46200',
                ]);

                $successCount++;
            }

            $message = "Import selesai! Berhasil: $successCount data, Gagal: $errorCount data";

            if ($errorCount > 0 && $successCount > 0) {
                return redirect()->route('users.index')
                    ->with('warning', $message . ' — ' . implode(', ', array_slice($errors, 0, 3)));
            } elseif ($errorCount > 0) {
                return redirect()->route('users.index')
                    ->with('error', $message);
            } else {
                return redirect()->route('users.index')
                    ->with('success', $message);
            }

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
