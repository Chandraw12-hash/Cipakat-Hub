<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukUmkm extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'kategori',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
        'unit_usaha',
        'nomor_wa',
        'alamat_toko',
        'is_active_wa',
        'is_active_web_order',
        'status',
        'created_by'
    ];

    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor untuk format harga
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
