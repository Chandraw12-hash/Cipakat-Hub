<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Keuangan;
use App\Models\Fasilitas;
use App\Traits\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    use Notifiable;

    // Halaman booking (untuk warga)
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('booking.index', compact('bookings'));
    }

    // Form booking
    public function create()
    {
        $fasilitas = Fasilitas::where('status', 'aktif')->get();
        return view('booking.create', compact('fasilitas'));
    }

    // Ambil jadwal booking untuk suatu fasilitas & tanggal
    public function getJadwal(Request $request)
    {
        $request->validate([
            'item' => 'required|string',
            'tanggal' => 'required|date'
        ]);

        $bookings = Booking::where('item', $request->item)
            ->where('tanggal_booking', $request->tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // Generate slot waktu (jam 08:00 - 20:00)
        $slots = [];
        $start = 8;
        $end = 20;

        for ($hour = $start; $hour < $end; $hour++) {
            $jamMulai = $hour . ':00';
            $jamSelesai = ($hour + 1) . ':00';

            $isBooked = $bookings->contains(function($booking) use ($hour) {
                $bookingStart = (int) date('H', strtotime($booking->jam_mulai));
                $bookingEnd = (int) date('H', strtotime($booking->jam_selesai));
                return $hour >= $bookingStart && $hour < $bookingEnd;
            });

            $slots[] = [
                'jam' => $jamMulai . ' - ' . $jamSelesai,
                'status' => $isBooked ? 'terbooking' : 'tersedia'
            ];
        }

        return response()->json($slots);
    }

    // Simpan booking
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'item' => 'required|string|max:255',
            'kategori' => 'required|string|in:olahraga,aula,peralatan',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jumlah' => 'required|integer|min:1',
            'harga' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        // Cek jenis booking
        $jenisBooking = $request->jenis_booking;

        // Jika full day, set jam otomatis 08:00 - 20:00
        if ($jenisBooking == 'full_day') {
            $jamMulai = '08:00';
            $jamSelesai = '20:00';
        } else {
            // Ambil jam dari hidden input (jadwal) atau manual
            $jamMulai = $request->jam_mulai;
            $jamSelesai = $request->jam_selesai;

            // Jika tidak ada dari hidden, ambil dari manual
            if (!$jamMulai || !$jamSelesai) {
                $jamMulai = $request->jam_mulai_manual;
                $jamSelesai = $request->jam_selesai_manual;
            }

            // Validasi jam
            if (!$jamMulai || !$jamSelesai) {
                return back()->with('error', 'Silakan pilih jam booking terlebih dahulu!')->withInput();
            }

            // Validasi jam selesai harus lebih dari jam mulai
            if (strtotime($jamSelesai) <= strtotime($jamMulai)) {
                return back()->with('error', 'Jam selesai harus lebih besar dari jam mulai!')->withInput();
            }
        }

        // Cek apakah sudah ada booking di tanggal dan jam yang sama
        $exists = Booking::where('item', $request->item)
            ->where('tanggal_booking', $request->tanggal_booking)
            ->where(function($q) use ($jamMulai, $jamSelesai) {
                $q->where(function($q2) use ($jamMulai, $jamSelesai) {
                    $q2->where('jam_mulai', '<', $jamSelesai)
                       ->where('jam_selesai', '>', $jamMulai);
                });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Maaf, jadwal sudah dibooking. Silakan pilih jadwal lain.')->withInput();
        }

        $harga = $request->harga ?? 0;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'item' => $request->item,
            'kategori' => $request->kategori,
            'tanggal_booking' => $request->tanggal_booking,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'jumlah' => $request->jumlah,
            'harga' => $harga,
            'keterangan' => $request->keterangan,
            'status' => 'pending',
            'status_pembayaran' => $harga > 0 ? 'belum_bayar' : 'lunas'
        ]);

        // Upload bukti pembayaran jika ada
        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $booking->update(['bukti_bayar' => $path]);
        }

        // Kirim notifikasi WA ke admin
        $this->notifyBookingCreated($booking);

        // Redirect ke halaman QRIS untuk pembayaran
        return redirect()->route('booking.qris', $booking->id)
            ->with('success', 'Booking berhasil! Silakan lakukan pembayaran dengan scan QR Code di bawah.');
    }

    // Detail booking
    public function show($id)
    {
        $booking = Booking::with('user')->findOrFail($id);

        if (Auth::id() != $booking->user_id && !in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        return view('booking.show', compact('booking'));
    }

    // Booking saya (warga)
    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('booking.my', compact('bookings'));
    }

    // Admin: daftar semua booking
    public function adminIndex(Request $request)
    {
        $query = Booking::with('user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->status_pembayaran) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        $bookings = $query->paginate(15);
        return view('booking.admin.index', compact('bookings'));
    }

    // Tampilkan halaman QRIS untuk pembayaran
    public function showQris($id)
    {
        $booking = Booking::findOrFail($id);

        // Cek akses: hanya pemilik booking atau admin/petugas
        if (Auth::id() != $booking->user_id && !in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        return view('booking.qris', [
            'bookingId' => $booking->id,
            'harga' => $booking->harga
        ]);
    }

    // Upload bukti pembayaran
    public function uploadBukti(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Cek akses: hanya pemilik booking
        if (Auth::id() != $booking->user_id) {
            abort(403);
        }

        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        $booking->update(['bukti_bayar' => $path]);

        return redirect()->route('booking.index')->with('success', 'Bukti pembayaran berhasil diupload. Silakan tunggu konfirmasi dari petugas.');
    }

    // Admin: konfirmasi booking (otomatis masuk keuangan)
    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);

        // Jika booking berbayar dan belum lunas, buat transaksi keuangan
        if ($booking->harga > 0 && $booking->status_pembayaran == 'belum_bayar') {
            // Cek apakah sudah pernah dibuat transaksinya
            $existing = Keuangan::where('booking_id', $booking->id)->first();

            if (!$existing) {
                // Buat transaksi pemasukan
                Keuangan::create([
                    'jenis' => 'pemasukan',
                    'kategori' => 'Booking Fasilitas',
                    'deskripsi' => 'Booking ' . $booking->item . ' oleh ' . $booking->user->name . ' (Tgl: ' . $booking->tanggal_booking . ')',
                    'jumlah' => $booking->harga,
                    'tanggal' => now(),
                    'created_by' => Auth::id(),
                    'booking_id' => $booking->id
                ]);

                $booking->update(['status_pembayaran' => 'lunas']);
            }
        }

        $this->notifyBookingStatusChanged($booking, 'confirmed');

        return redirect()->back()->with('success', 'Booking berhasil dikonfirmasi. ' . ($booking->harga > 0 ? 'Transaksi keuangan otomatis ditambahkan.' : ''));
    }

    // Admin: batalkan booking
    public function cancel(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'cancelled',
            'catatan_admin' => $request->catatan_admin
        ]);

        $this->notifyBookingStatusChanged($booking, 'cancelled');

        return redirect()->back()->with('success', 'Booking dibatalkan.');
    }

    // Admin: selesaikan booking
    public function complete($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'selesai']);

        $this->notifyBookingStatusChanged($booking, 'selesai');

        return redirect()->back()->with('success', 'Booking ditandai selesai.');
    }
}
