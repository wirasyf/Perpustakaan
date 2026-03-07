<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Transaction;
use App\Models\Report;
use App\Models\Visit;

use App\Exports\TransaksiExport;
use App\Exports\KehilanganExport;
use App\Exports\KunjunganExport;
use App\Exports\AnggotaDiterimaExport;
use App\Exports\BukuExport;


class CetakController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // =====================================================
    // 🔹 FILTER - halaman preview dengan data
    // =====================================================
    public function filterTransaksi(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        $kelas = $request->get('kelas');
        
        $transactions = Transaction::with('user', 'book')
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_peminjaman', [$start, $end]))
            ->when($kelas && $kelas !== 'semua', function($q) use ($kelas) {
                $q->whereHas('user', fn($uq) => $uq->where('kelas', $kelas));
            })
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();

        $kelasList = Transaction::join('users', 'transactions.user_id', '=', 'users.id')
            ->whereNotNull('users.kelas')
            ->select('users.kelas')
            ->distinct()
            ->orderBy('users.kelas')
            ->pluck('users.kelas');

        return view('cetak.laporan.cetak-transaksi', compact('transactions', 'kelasList'));
    }

    public function filterKehilangan(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        $kelas = $request->get('kelas');
        
        $reports = Report::with(['user', 'transaction.book'])
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->when($kelas && $kelas !== 'semua', function($q) use ($kelas) {
                $q->whereHas('transaction.user', fn($uq) => $uq->where('kelas', $kelas));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $kelasList = Report::join('transactions', 'reports.transactions_id', '=', 'transactions.id')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->whereNotNull('users.kelas')
            ->select('users.kelas')
            ->distinct()
            ->orderBy('users.kelas')
            ->pluck('users.kelas');

        return view('cetak.laporan.cetak-kehilangan', compact('reports', 'kelasList'));
    }

    public function filterKunjungan(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        $kelas = $request->get('kelas');
        
        $visits = Visit::with('user', 'transaction')
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_datang', [$start, $end]))
            ->when($kelas && $kelas !== 'semua', function($q) use ($kelas) {
                $q->whereHas('user', fn($uq) => $uq->where('kelas', $kelas));
            })
            ->orderBy('tanggal_datang', 'desc')
            ->get();

        $kelasList = Visit::join('users', 'visit.user_id', '=', 'users.id')
            ->whereNotNull('users.kelas')
            ->select('users.kelas')
            ->distinct()
            ->orderBy('users.kelas')
            ->pluck('users.kelas');

        return view('cetak.laporan.cetak-daftar-pengunjung', compact('visits', 'kelasList'));
    }

    // =====================================================
    // 🔹 TRANSAKSI - CETAK PER ID (nota)
    // =====================================================

    public function cetakNotaPdf($id, $jenis = 'peminjaman')
    {
        $transaction = Transaction::with('user', 'book')->findOrFail($id);

        return Pdf::loadView('cetak.nota.cetak-transaksi', [
            'transaction' => $transaction,
            'jenis'       => $jenis
        ])
        ->setPaper('A5', 'portrait')
        ->stream("nota-{$jenis}-{$id}.pdf");
    }

    // =====================================================
    // 🔹 LAPORAN KESELURUHAN + EXPORT
    // =====================================================

    public function transaksiExportPdf(Request $request)
    {
        $transactions = $this->getTransactions($request);
        $pdf = Pdf::loadView('cetak.pdf.transaction-report', compact('transactions'))
                  ->setPaper('A4', 'landscape');
        return $pdf->download('laporan_transaksi.pdf');
    }

    // ✅ EXCEL TRANSAKSI (maatwebsite/excel v3)
    public function transaksiExportExcel(Request $request)
    {
        $status    = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $namaFile = match($status) {
            'belum_dikembalikan' => 'transaksi-belum-dikembalikan.xlsx',
            'sudah_dikembalikan' => 'transaksi-sudah-dikembalikan.xlsx',
            default              => 'laporan-transaksi.xlsx',
        };

        return Excel::download(
            new TransaksiExport($status, $startDate, $endDate, $request->get('kelas')),
            $namaFile
        );
    }

    private function getTransactions(Request $request)
    {
        $start  = $request->get('start_date');
        $end    = $request->get('end_date');
        $status = $request->get('status');
        $kelas  = $request->get('kelas');

        return Transaction::with('user', 'book')
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_peminjaman', [$start, $end]))
            ->when($status && $status !== 'semua', fn($q) => $q->where('status', $status))
            ->when($kelas && $kelas !== 'semua', function($q) use ($kelas) {
                $q->whereHas('user', fn($uq) => $uq->where('kelas', $kelas));
            })
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();
    }

    // =====================================================
    // 🔹 KEHILANGAN
    // =====================================================

    public function pengembalianHilangPdf($id)
    {
        $report = Report::with(['user', 'transaction.book'])->findOrFail($id);

        return Pdf::loadView('cetak.nota.cetak-buku-hilang', compact('report'))
                  ->setPaper('A5', 'portrait')
                  ->stream("pengembalian-buku-hilang-{$id}.pdf");
    }

    public function kehilanganExportPdf(Request $request)
    {
        $reports = $this->getReports($request);

        $pdf = Pdf::loadView('cetak.pdf.report', compact('reports'))
                ->setPaper('A4', 'landscape');

        return $pdf->download('laporan-kehilangan.pdf');
    }

    // ✅ EXCEL KEHILANGAN (maatwebsite/excel v3)
    public function kehilanganExportExcel(Request $request)
    {
        $status    = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $namaFile = match($status) {
            'belum_dikembalikan' => 'kehilangan-belum-diganti.xlsx',
            'sudah_dikembalikan' => 'kehilangan-sudah-diganti.xlsx',
            default              => 'laporan-kehilangan.xlsx',
        };

        return Excel::download(
            new KehilanganExport($status, $startDate, $endDate, $request->get('kelas')),
            $namaFile
        );
    }

    private function getReports(Request $request)
    {
        $start  = $request->get('start_date');
        $end    = $request->get('end_date');
        $status = $request->get('status');
        $kelas  = $request->get('kelas');

        return Report::with(['user', 'transaction.user', 'transaction.book'])
            ->when($status && $status !== 'semua', fn($q) => $q->where('status', $status))
            ->when($start && $end, fn($q) => $q->whereBetween('created_at', [$start, $end]))
            ->when($kelas && $kelas !== 'semua', function($q) use ($kelas) {
                $q->whereHas('transaction.user', fn($uq) => $uq->where('kelas', $kelas));
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
    // =====================================================
    // 🔹 KUNJUNGAN
    // =====================================================

public function kunjunganExportPdf(Request $request)
{
    $hari  = $request->get('hari');
    $bulan = $request->get('bulan');
    $tahun = $request->get('tahun');
    $kelas = $request->get('kelas');

    $visits = Visit::with('user')
        ->when($hari, function($q) use ($hari) {
            if ($hari === 'today') {
                $q->whereDate('tanggal_datang', today());
            } else {
                $q->whereDay('tanggal_datang', $hari);
            }
        })
        ->when($bulan && $bulan !== 'semua', fn($q) => $q->whereMonth('tanggal_datang', $bulan))
        ->when($tahun && $tahun !== 'semua', fn($q) => $q->whereYear('tanggal_datang', $tahun))
        ->when($kelas && $kelas !== 'semua', function($q) use ($kelas) {
            $q->whereHas('user', fn($uq) => $uq->where('kelas', $kelas));
        })
        ->orderBy('tanggal_datang', 'desc')
        ->get();

    $pdf = Pdf::loadView('cetak.pdf.visit', compact('visits', 'hari', 'bulan', 'tahun', 'kelas'))
              ->setPaper('A4', 'landscape');

    return $pdf->download('laporan-kunjungan.pdf');
}

    // EXCEL
    public function kunjunganExportExcel(Request $request)
    {
        return Excel::download(new KunjunganExport(
            $request->get('hari'),
            $request->get('bulan'),
            $request->get('tahun'),
            $request->get('kelas')
        ), 'laporan-kunjungan.xlsx');
    }

    private function getVisits(Request $request)
    {
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        $kelas = $request->get('kelas');

        return Visit::with('user', 'transaction')
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_datang', [$start, $end]))
            ->when($kelas && $kelas !== 'semua', function($q) use ($kelas) {
                $q->whereHas('user', fn($uq) => $uq->where('kelas', $kelas));
            })
            ->orderBy('tanggal_datang', 'desc')
            ->get();
    }

    // =====================================================
    // 🔹 ROUTE ALIASES (SUDAH DIBENARKAN!)
    // =====================================================
    public function transaksiPrint()     { return $this->transactionReport(request()); }
    public function transaksiPdf()       { return $this->transaksiExportPdf(request()); }
    public function kehilanganPrint()    { return $this->reportPrint(); }
    public function kehilanganPdf()      { return $this->kehilanganExportPdf(request()); }
    public function kehilanganExcel()    { return $this->kehilanganExportExcel(request()); }
    public function kunjunganPrint()     { return $this->visitPrint(); }
    public function kunjunganPdf()       { return $this->kunjunganExportPdf(request()); }
    public function kunjunganExcel()     { return $this->kunjunganExportExcel(request()); }

    public function kartuSiswa()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('cetak.cetak-kartu', compact('user'));
    }

    public function downloadKartuSiswa()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $pdf = Pdf::loadView('cetak.cetak-kartu', compact('user'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("kartu-anggota-{$user->nis_nisn}.pdf");
    }

    // =====================================================
    // 🔹 CETAK KARTU ANGGOTA (ADMIN)
    // =====================================================

    public function exportKartuAdmin($id)
    {
        if (\Illuminate\Support\Facades\Auth::user()?->role !== 'admin') abort(403);

        $user = \App\Models\User::findOrFail($id);
        $pdf = Pdf::loadView('cetak.cetak-kartu', compact('user'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("kartu-anggota-{$user->nis_nisn}.pdf");
    }

    // =====================================================
    // 🔹 EXPORT EXCEL DATA ANGGOTA DITERIMA
    // =====================================================

    public function anggotaDiterimaExcel(Request $request)
    {
        
    $kelas = $request->input('kelas', 'semua');

    $namaFile = $kelas === 'semua'
        ? 'data-anggota-diterima.xlsx'
        : 'data-anggota-' . str_replace(' ', '-', strtolower($kelas)) . '.xlsx';

    return Excel::download(new AnggotaDiterimaExport($kelas), $namaFile);
}

    // =====================================================
    // 🔹 EXPORT EXCEL DATA BUKU
    // =====================================================

    public function bukuExcel()
    {
        if (\Illuminate\Support\Facades\Auth::user()?->role !== 'admin') abort(403);

        return Excel::download(new BukuExport, 'data-buku.xlsx');
    }
}
