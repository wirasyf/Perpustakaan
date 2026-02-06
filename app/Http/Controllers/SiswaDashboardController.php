<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kunjungan;
use App\Models\LaporanKehilangan;
use Carbon\Carbon;

use Illuminate\Http\Request;

class SiswaDashboardController extends Controller
{
     public function index()
    {
        $userId = auth()->id();

        /* =======================
         * RINGKASAN DATA
         * ======================= */

        // Total buku yang sedang dipinjam siswa
        $totalDipinjam = Transaksi::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->count();

        // Total buku terlambat
        $totalTerlambat = Transaksi::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', Carbon::today())
            ->count();

        // Status kunjungan hari ini
        $kunjunganHariIni = Kunjungan::where('user_id', $userId)
            ->whereDate('tanggal_kunjungan', Carbon::today())
            ->exists();

        /* =======================
         * LIST DATA TERBARU
         * ======================= */

        // Riwayat peminjaman terakhir
        $riwayatPeminjaman = Transaksi::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Laporan kehilangan terbaru
        $laporanKehilanganTerbaru = LaporanKehilangan::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        /* =======================
         * KIRIM KE VIEW
         * ======================= */

        return view('siswa.dashboard', compact(
            'totalDipinjam',
            'totalTerlambat',
            'kunjunganHariIni',
            'riwayatPeminjaman',
            'laporanKehilanganTerbaru'
        ));
    }
}
