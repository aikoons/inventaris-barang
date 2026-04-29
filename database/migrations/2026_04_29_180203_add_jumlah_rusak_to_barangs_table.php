<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_rusak_ringan')->default(0)->after('jumlah_tersedia');
            $table->unsignedInteger('jumlah_rusak_berat')->default(0)->after('jumlah_rusak_ringan');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['jumlah_rusak_ringan', 'jumlah_rusak_berat']);
        });
    }
};
