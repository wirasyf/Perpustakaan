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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('books')->onDelete('cascade');
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_pengembalian')->nullable();
            $table->enum('jenis_transaksi', ['dipinjam', 'dikembalikan']);
            // The original enum definition for 'status' is removed here.
            // The DB::statement below will define or alter the column.
            $table->timestamps();
        });

        // This DB::statement should be outside the Schema::create closure
        // if it's meant to alter an existing column, or if it's defining
        // the column after creation. Given the context, it's likely
        // intended to define the column's enum values and constraints.
        // For initial creation, it's better to use $table->enum directly.
        // However, following the provided Code Edit, we'll place it after creation.
        DB::statement("ALTER TABLE transactions MODIFY status ENUM(
            'sudah_dikembalikan',
            'belum_dikembalikan',
            'menunggu_konfirmasi',
            'terlambat'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
