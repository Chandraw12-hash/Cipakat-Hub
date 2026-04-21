<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fasilitas;

class FasilitasSeeder extends Seeder
{
    public function run()
    {
        $fasilitas = [
            ['nama' => 'Lapangan Sepak Bola', 'kategori' => 'olahraga', 'harga_per_jam' => 50000, 'harga_full_day' => 300000],
            ['nama' => 'Lapangan Voli', 'kategori' => 'olahraga', 'harga_per_jam' => 30000, 'harga_full_day' => 200000],
            ['nama' => 'Lapangan Bulu Tangkis', 'kategori' => 'olahraga', 'harga_per_jam' => 25000, 'harga_full_day' => 150000],
            ['nama' => 'Aula Desa', 'kategori' => 'aula', 'harga_per_jam' => 100000, 'harga_full_day' => 500000],
            ['nama' => 'Sound System', 'kategori' => 'peralatan', 'harga_per_jam' => 25000, 'harga_full_day' => 150000],
            ['nama' => 'Tenda', 'kategori' => 'peralatan', 'harga_per_jam' => 20000, 'harga_full_day' => 100000],
            ['nama' => 'Kursi', 'kategori' => 'peralatan', 'harga_per_jam' => 5000, 'harga_full_day' => 30000],
        ];

        foreach ($fasilitas as $item) {
            Fasilitas::create($item);
        }
    }
}
