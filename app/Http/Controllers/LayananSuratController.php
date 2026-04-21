<?php

namespace App\Http\Controllers;

use App\Models\LayananSurat;
use App\Traits\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LayananSuratController extends Controller
{
    use Notifiable;

    // Halaman pengajuan surat (untuk warga)
    public function index()
    {
        $surats = LayananSurat::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('layanan.index', compact('surats'));
    }

    // Form pengajuan surat
    public function create()
    {
        return view('layanan.create');
    }

    // Simpan pengajuan surat
    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $filePath = null;
        if ($request->hasFile('file_pendukung')) {
            $filePath = $request->file('file_pendukung')->store('surat', 'public');
        }

        $surat = LayananSurat::create([
            'user_id' => Auth::id(),
            'jenis_surat' => $request->jenis_surat,
            'keterangan' => $request->keterangan,
            'file_pendukung' => $filePath,
            'status' => 'pending'
        ]);

        // Kirim notifikasi ke admin
        $this->notifySuratCreated($surat);

        return redirect()->route('layanan.index')
            ->with('success', 'Pengajuan surat berhasil. Silakan tunggu konfirmasi dari petugas.');
    }

    // Detail pengajuan surat
    public function show($id)
    {
        $surat = LayananSurat::with('user')->findOrFail($id);

        if (Auth::id() != $surat->user_id && !in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        return view('layanan.show', compact('surat'));
    }

    // Tracking status surat
    public function tracking($id)
    {
        $surat = LayananSurat::findOrFail($id);

        if (Auth::id() != $surat->user_id && !in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        return view('layanan.tracking', compact('surat'));
    }

    // Admin: daftar semua pengajuan surat
    public function adminIndex(Request $request)
    {
        $query = LayananSurat::with('user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $surats = $query->paginate(15);
        return view('layanan.admin.index', compact('surats'));
    }

    // Admin: proses surat (approve) - TIDAK KIRIM NOTIFIKASI
    public function approve($id)
    {
        $surat = LayananSurat::findOrFail($id);
        $surat->update(['status' => 'diproses']);

        return redirect()->back()->with('success', 'Pengajuan surat diproses.');
    }

    // Admin: reject surat
    public function reject(Request $request, $id)
    {
        $surat = LayananSurat::findOrFail($id);
        $surat->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ]);

        // Kirim notifikasi ke warga (DITOLAK)
        $this->notifySuratStatusChanged($surat, 'ditolak');

        return redirect()->back()->with('success', 'Pengajuan surat ditolak.');
    }

    // Admin: selesaikan surat + generate PDF + kirim WA link download
    public function complete($id)
    {
        $surat = LayananSurat::with('user')->findOrFail($id);

        // Update status
        $surat->update(['status' => 'selesai']);

        // Generate PDF Surat
        $pdfContent = $this->generateSuratPdf($surat);

        // Simpan PDF
        $pdfPath = 'surat/surat_' . $surat->id . '_' . date('YmdHis') . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdfContent);

        // Update path file surat di database
        $surat->update(['file_surat' => $pdfPath]);

        // Kirim notifikasi WA ke warga (SELESAI + LINK DOWNLOAD)
        $userPhone = $surat->user->phone ?? '';

        if ($userPhone) {
            $message = "*Cipakat Hub - Notifikasi*\n\n";
            $message .= "Pengajuan surat Anda telah SELESAI.\n";
            $message .= "Jenis: {$surat->jenis_surat}\n";
            $message .= "Tanggal: {$surat->created_at->format('d/m/Y')}\n\n";
            $message .= "Download surat: " . route('layanan.download', $surat->id);

            // Kirim WA biasa (tanpa file)
            $this->sendWhatsApp($userPhone, $message);
        }

        return redirect()->back()->with('success', 'Pengajuan surat selesai. Surat sudah dikirim ke WhatsApp warga.');
    }

    // Generate PDF Surat
    protected function generateSuratPdf($surat)
    {
        $data = [
            'surat' => $surat,
            'kepalaDesa' => env('KEPALA_DESA', 'Kepala Desa Cipakat'),
            'tanggal_cetak' => date('d F Y')
        ];

        // Template default
        $pdf = Pdf::loadView('surat.template', $data);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->output();
    }

    // Download surat PDF
    public function download($id)
    {
        $surat = LayananSurat::findOrFail($id);

        // Cek akses: hanya pemilik atau admin/petugas
        if (Auth::id() != $surat->user_id && !in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        if (!$surat->file_surat) {
            abort(404, 'File surat belum tersedia');
        }

        return response()->download(storage_path('app/public/' . $surat->file_surat));
    }
}
