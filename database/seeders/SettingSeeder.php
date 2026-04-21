<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Informasi Desa
            ['key' => 'desa_nama', 'value' => 'Desa Cipakat', 'type' => 'text', 'group' => 'general'],
            ['key' => 'desa_kecamatan', 'value' => 'Kecamatan Setu', 'type' => 'text', 'group' => 'general'],
            ['key' => 'desa_kabupaten', 'value' => 'Kabupaten Bekasi', 'type' => 'text', 'group' => 'general'],
            ['key' => 'desa_kode_pos', 'value' => '17320', 'type' => 'text', 'group' => 'general'],

            // Kontak
            ['key' => 'kontak_telepon', 'value' => '081234567890', 'type' => 'text', 'group' => 'kontak'],
            ['key' => 'kontak_email', 'value' => 'info@cipakathub.com', 'type' => 'email', 'group' => 'kontak'],
            ['key' => 'kontak_alamat', 'value' => 'Jl. Desa Cipakat No. 1, RT 01 RW 02', 'type' => 'textarea', 'group' => 'kontak'],
            ['key' => 'kontak_maps', 'value' => 'https://maps.google.com', 'type' => 'url', 'group' => 'kontak'],

            // Sosial Media
            ['key' => 'sosmed_facebook', 'value' => 'https://facebook.com', 'type' => 'url', 'group' => 'sosmed'],
            ['key' => 'sosmed_instagram', 'value' => 'https://instagram.com', 'type' => 'url', 'group' => 'sosmed'],
            ['key' => 'sosmed_youtube', 'value' => 'https://youtube.com', 'type' => 'url', 'group' => 'sosmed'],
            ['key' => 'sosmed_tiktok', 'value' => 'https://tiktok.com', 'type' => 'url', 'group' => 'sosmed'],

            // Logo & Gambar
            ['key' => 'logo', 'value' => null, 'type' => 'image', 'group' => 'tampilan'],
            ['key' => 'favicon', 'value' => null, 'type' => 'image', 'group' => 'tampilan'],
            ['key' => 'hero_background', 'value' => null, 'type' => 'image', 'group' => 'tampilan'],

            // Lainnya
            ['key' => 'kepala_desa', 'value' => 'Kepala Desa Cipakat', 'type' => 'text', 'group' => 'umum'],
            ['key' => 'wa_notifikasi', 'value' => 'true', 'type' => 'checkbox', 'group' => 'notifikasi'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
