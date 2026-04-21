<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis',
        'kategori',
        'deskripsi',
        'jumlah',
        'tanggal',
        'bukti',
        'created_by',
        'booking_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
