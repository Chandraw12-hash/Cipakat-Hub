<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'kategori', 'harga_per_jam', 'harga_full_day', 'deskripsi', 'gambar', 'status'
    ];
}
