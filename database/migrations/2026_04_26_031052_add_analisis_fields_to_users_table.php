<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Data Pekerjaan
            $table->string('status_pekerjaan')->nullable()->after('pekerjaan'); // bekerja, tidak_bekerja, mahasiswa, pensiun
            $table->decimal('pendapatan_bulanan', 15, 0)->nullable()->after('status_pekerjaan');

            // Data Pendidikan
            $table->string('pendidikan_terakhir')->nullable()->after('pendapatan_bulanan'); // SD, SMP, SMA, D3, S1, S2

            // Data Sosial
            $table->string('status_rumah')->nullable()->after('pendidikan_terakhir'); // milik_sendiri, kontrak, keluarga
            $table->string('kategori_sosial')->nullable()->after('status_rumah'); // miskin, rentan, mampu
            $table->boolean('is_penerima_bantuan')->default(false)->after('kategori_sosial');
            $table->integer('jumlah_tanggungan')->default(0)->after('is_penerima_bantuan');

            // Data Keluarga
            $table->string('kepala_keluarga_nik')->nullable()->after('jumlah_tanggungan');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status_pekerjaan', 'pendapatan_bulanan', 'pendidikan_terakhir',
                'status_rumah', 'kategori_sosial', 'is_penerima_bantuan',
                'jumlah_tanggungan', 'kepala_keluarga_nik'
            ]);
        });
    }
};
