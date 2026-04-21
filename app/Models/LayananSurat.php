<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananSurat extends Model
{
    use HasFactory;

   protected $fillable = [
    'user_id',
    'jenis_surat',
    'keterangan',
    'file_pendukung',
    'file_surat',
    'status',
    'catatan_admin'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">Pending</span>',
            'diproses' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">Diproses</span>',
            'selesai' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Selesai</span>',
            'ditolak' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Ditolak</span>',
            default => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">' . $this->status . '</span>'
        };
    }
}
