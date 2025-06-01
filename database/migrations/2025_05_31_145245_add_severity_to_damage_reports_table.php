<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('damage_reports', function (Blueprint $table) {
            // Tambahkan kolom setelah kolom 'description' atau sesuaikan posisinya
            $table->string('severity')->default('ringan')->after('description');
            // Pilihan nilai bisa: 'ringan', 'sedang', 'berat'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('damage_reports', function (Blueprint $table) {
            $table->dropColumn('severity');
        });
    }
};