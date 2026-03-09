<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY status ENUM(
            'sudah_dikembalikan',
            'belum_dikembalikan',
            'menunggu_konfirmasi',
            'terlambat',
            'buku_hilang'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY status ENUM(
            'sudah_dikembalikan',
            'belum_dikembalikan',
            'menunggu_konfirmasi',
            'terlambat'
        ) NOT NULL");
    }
};
