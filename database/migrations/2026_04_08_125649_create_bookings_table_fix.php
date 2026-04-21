<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('item');
                $table->string('kategori');
                $table->date('tanggal_booking');
                $table->time('jam_mulai');
                $table->time('jam_selesai');
                $table->integer('jumlah')->default(1);
                $table->text('keterangan')->nullable();
                $table->enum('status', ['pending', 'confirmed', 'cancelled', 'selesai'])->default('pending');
                $table->text('catatan_admin')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
