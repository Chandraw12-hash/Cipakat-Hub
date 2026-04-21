<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduk extends Model
{
    use HasFactory;

    protected $table = 'order_produks';

    protected $fillable = [
        'produk_id',
        'user_id',
        'jumlah',
        'total_harga',
        'keterangan',
        'status',
        'catatan_admin'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'total_harga' => 'integer'
    ];

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(ProdukUmkm::class, 'produk_id');
    }

    // Relasi ke user (pemesan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Status badge untuk tampilan
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Pending</span>',
            'confirmed' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Dikonfirmasi</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Dibatalkan</span>',
            'selesai' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">Selesai</span>',
            default => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">' . $this->status . '</span>'
        };
    }

    // Format total harga
    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
}
