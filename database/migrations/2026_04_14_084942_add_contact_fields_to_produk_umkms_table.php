<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk_umkms', function (Blueprint $table) {
            $table->string('nomor_wa')->nullable()->after('unit_usaha');
            $table->string('alamat_toko')->nullable()->after('nomor_wa');
            $table->boolean('is_active_wa')->default(true)->after('alamat_toko');
            $table->boolean('is_active_web_order')->default(true)->after('is_active_wa');
        });
    }

    public function down(): void
    {
        Schema::table('produk_umkms', function (Blueprint $table) {
            $table->dropColumn(['nomor_wa', 'alamat_toko', 'is_active_wa', 'is_active_web_order']);
        });
    }
};
