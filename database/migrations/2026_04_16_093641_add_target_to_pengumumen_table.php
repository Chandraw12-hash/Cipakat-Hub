<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengumumen', function (Blueprint $table) {
            $table->enum('target', ['publik', 'internal'])->default('publik')->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('pengumumen', function (Blueprint $table) {
            $table->dropColumn('target');
        });
    }
};
