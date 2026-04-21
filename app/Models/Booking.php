<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item',
        'kategori',
        'tanggal_booking',
        'jam_mulai',
        'jam_selesai',
        'jumlah',
        'harga',
        'keterangan',
        'status',
        'status_pembayaran',
        'bukti_bayar',
        'catatan_admin'
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Pending</span>',
            'confirmed' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Dikonfirmasi</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Dibatalkan</span>',
            'selesai' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">Selesai</span>',
            default => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">' . $this->status . '</span>'
        };
    }
}
