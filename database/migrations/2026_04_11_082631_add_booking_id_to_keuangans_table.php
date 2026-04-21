<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('keuangans', function (Blueprint $table) {
            $table->foreignId('booking_id')->nullable()->after('created_by')->constrained('bookings')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('keuangans', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn('booking_id');
        });
    }
};
