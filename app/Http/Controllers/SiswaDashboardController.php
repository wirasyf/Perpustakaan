<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Visit;
use App\Models\Report;
use Carbon\Carbon;
use App\Models\Book;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()?->role !== 'anggota') abort(403);

        $userId = Auth::id();

        /* =======================
         * RINGKASAN DATA
         * ======================= */

        // Total buku yang sedang dipinjam siswa (status belum_dikembalikan atau terlambat)
        $totalDipinjam = Transaction::where('user_id', $userId)
            ->where('jenis_transaksi', 'dipinjam')
            ->whereIn('status', ['belum_dikembalikan', 'terlambat', 'menunggu_konfirmasi'])
            ->count();
            
        // Total buku hilang (status buku_hilang)
        $totalBukuHilang = Transaction::where('user_id', $userId)
            ->where('status', 'buku_hilang')
            ->count();

        //total pengembalian buku
        $totalPengembalian = Transaction::where('user_id', $userId)
            ->where('jenis_transaksi', 'dikembalikan')
            ->count();

        // Total buku terlambat
        $totalTerlambat = Transaction::where('user_id', $userId)
            ->whereIn('status', ['belum_dikembalikan', 'terlambat'])
            ->whereDate('tanggal_jatuh_tempo', '<', Carbon::today())
            ->count();

        /* =======================
         * LIST DATA TERBARU
         * ======================= */

        // Riwayat peminjaman terakhir
        $riwayatPeminjaman = Transaction::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

            
        // Status kunjungan hari ini
        $kunjunganHariIni = Visit::where('user_id', $userId)
            ->whereDate('tanggal_datang', Carbon::today())
            ->exists();

        // Total kunjungan siswa
        $totalKunjungan = Visit::where('user_id', $userId)->count();


        /* =======================
         * KIRIM KE VIEW
         * ======================= */

        return view('siswa.dashboard-siswa', compact(
    'totalDipinjam',
    'totalTerlambat',
    'totalPengembalian',
    'totalBukuHilang',
    'kunjunganHariIni',
    'riwayatPeminjaman'
));
    }
}
