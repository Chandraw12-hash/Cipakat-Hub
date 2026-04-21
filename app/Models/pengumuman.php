<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumumen';

    protected $fillable = [
        'judul',
        'isi',
        'jenis',
        'gambar',
        'target',
        'created_by',
        'status',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getJenisBadgeAttribute()
    {
        return match($this->jenis) {
            'penting' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Penting</span>',
            'biasa' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">Biasa</span>',
            default => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">' . $this->jenis . '</span>'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'published' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Published</span>',
            'draft' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Draft</span>',
            default => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">' . $this->status . '</span>'
        };
    }
}
