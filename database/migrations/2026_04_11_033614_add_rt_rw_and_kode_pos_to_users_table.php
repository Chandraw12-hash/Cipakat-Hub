<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'rt_rw')) {
                $table->string('rt_rw')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('users', 'kode_pos')) {
                $table->string('kode_pos', 10)->nullable()->after('rt_rw');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rt_rw', 'kode_pos']);
        });
    }
};
