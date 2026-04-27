<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek apakah kolom sudah ada sebelum menambahkan
        if (!Schema::hasColumn('users', 'kepala_keluarga_nik')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('kepala_keluarga_nik', 20)->nullable()->after('nik');
            });
        }

        if (!Schema::hasColumn('users', 'status_keluarga')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status_keluarga', ['kepala_keluarga', 'istri', 'anak', 'lainnya'])->default('lainnya')->after('kepala_keluarga_nik');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kepala_keluarga_nik', 'status_keluarga']);
        });
    }
};
