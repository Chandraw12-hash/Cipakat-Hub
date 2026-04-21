<?php

namespace App\Traits;

use App\Helpers\WhatsAppHelper;

trait Notifiable
{
    protected $whatsapp;

    public function __construct()
    {
        $this->whatsapp = new WhatsAppHelper();
    }

    public function sendWhatsApp($phone, $message)
    {
        return $this->whatsapp->send($phone, $message);
    }

    // Kirim WA dengan file (PDF, gambar, dll)
    public function sendWhatsAppWithFile($phone, $message, $filePath)
    {
        return $this->whatsapp->sendWithFile($phone, $message, $filePath);
    }

    // Notifikasi untuk pengajuan surat ke admin
    public function notifySuratCreated($surat)
    {
        $adminPhone = env('ADMIN_PHONE', '');

        $message = "*Cipakat Hub - Notifikasi*\n\n";
        $message .= "Ada pengajuan surat baru!\n";
        $message .= "Jenis: {$surat->jenis_surat}\n";
        $message .= "Dari: {$surat->user->name}\n";
        $message .= "Tanggal: {$surat->created_at->format('d/m/Y H:i')}\n\n";
        $message .= "Segera proses di: " . route('layanan.admin');

        if ($adminPhone) {
            $this->sendWhatsApp($adminPhone, $message);
        }
    }

    // Notifikasi status surat ke warga (hanya DITOLAK dan SELESAI)
    public function notifySuratStatusChanged($surat, $status)
    {
        $userPhone = $surat->user->phone ?? '';

        $statusText = match($status) {
            'selesai' => 'SELESAI',
            'ditolak' => 'DITOLAK',
            default => null
        };

        if (!$statusText) {
            return;
        }

        $message = "*Cipakat Hub - Notifikasi*\n\n";
        $message .= "Pengajuan surat Anda {$statusText}\n";
        $message .= "Jenis: {$surat->jenis_surat}\n";
        $message .= "Tanggal: {$surat->created_at->format('d/m/Y')}\n\n";
        $message .= "Cek detail: " . route('layanan.show', $surat->id);

        if ($userPhone) {
            $this->sendWhatsApp($userPhone, $message);
        }
    }

    // Notifikasi booking baru ke admin
    public function notifyBookingCreated($booking)
    {
        $adminPhone = env('ADMIN_PHONE', '');

        $message = "*Cipakat Hub - Notifikasi*\n\n";
        $message .= "Ada booking baru!\n";
        $message .= "Fasilitas: {$booking->item}\n";
        $message .= "Dari: {$booking->user->name}\n";
        $message .= "Tanggal: {$booking->tanggal_booking->format('d/m/Y')}\n";
        $message .= "Jam: {$booking->jam_mulai} - {$booking->jam_selesai}\n\n";
        $message .= "Segera konfirmasi di: " . route('booking.admin');

        if ($adminPhone) {
            $this->sendWhatsApp($adminPhone, $message);
        }
    }

    // Notifikasi status booking ke warga
    public function notifyBookingStatusChanged($booking, $status)
    {
        $userPhone = $booking->user->phone ?? '';

        $statusText = match($status) {
            'confirmed' => 'DIKONFIRMASI',
            'cancelled' => 'DIBATALKAN',
            'selesai' => 'SELESAI',
            default => 'DIPROSES'
        };

        $message = "*Cipakat Hub - Notifikasi*\n\n";
        $message .= "Booking Anda {$statusText}\n";
        $message .= "Fasilitas: {$booking->item}\n";
        $message .= "Tanggal: {$booking->tanggal_booking->format('d/m/Y')}\n\n";
        $message .= "Cek detail: " . route('booking.show', $booking->id);

        if ($userPhone) {
            $this->sendWhatsApp($userPhone, $message);
        }
    }
}
