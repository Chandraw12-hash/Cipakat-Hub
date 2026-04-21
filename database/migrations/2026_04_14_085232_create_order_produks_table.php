<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk_umkms');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('jumlah');
            $table->bigInteger('total_harga');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'selesai'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_produks');
    }
};
