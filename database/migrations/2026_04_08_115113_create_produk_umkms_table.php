<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('produk_umkms')) {
            Schema::create('produk_umkms', function (Blueprint $table) {
                $table->id();
                $table->string('nama_produk');
                $table->string('kategori');
                $table->text('deskripsi')->nullable();
                $table->bigInteger('harga');
                $table->integer('stok')->default(0);
                $table->string('gambar')->nullable();
                $table->string('unit_usaha')->nullable();
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->foreignId('created_by')->constrained('users');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_umkms');
    }
};
