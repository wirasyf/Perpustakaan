<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class LaporanKehilanganController extends Controller
{
    public function create()
    {
        return view('siswa.laporan-kehilangan.create');
    }

    public function store(Request $request)
    {
    $request->validate([
        'transactions_id' => 'required|exists:transactions,id',
        'tanggal_ganti'   => [
            'required',
            'date',
            // Validasi server-side: minimal 5 hari dari sekarang
            'after_or_equal:' . now()->addDays(5)->format('Y-m-d'),
        ],
        'keterangan' => 'required|string|max:500'
    ], [
        'tanggal_ganti.after_or_equal' => 'Tanggal mengganti buku minimal 5 hari dari sekarang (' . now()->addDays(5)->translatedFormat('d F Y') . ').',
    ]);

        // Pastikan user hanya bisa membuat laporan untuk transaksi mereka sendiri
        $transaction = \App\Models\Transaction::where('id', $request->transactions_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

        // Cek apakah laporan untuk transaksi ini sudah ada
        $sudahAda = \App\Models\Report::where('transactions_id', $request->transactions_id)
            ->whereIn('status', ['belum_dikembalikan', 'pending', 'approved'])
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Laporan kehilangan untuk buku ini sudah ada.');
        }

        Report::create([
            'user_id' => Auth::id(),
            'transactions_id' => $request->transactions_id,
            'tanggal_ganti' => $request->tanggal_ganti,
            'keterangan' => $request->keterangan,
            'status' => 'pending'
        ]);

        // Update status transaksi menjadi 'buku_hilang'
        $transaction->update(['status' => 'buku_hilang']);

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Laporan kehilangan berhasil dibuat. Silakan tunggu konfirmasi penggantian buku.');
    }

    public function index(Request $request)
    {
        $query = Report::where('user_id', Auth::id())
            ->with('transaction.bookItem.book');

        // Filter search (judul buku / keterangan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('transaction.bookItem.book', fn($qq) => $qq->where('judul', 'like', "%{$search}%"))
                ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        // Filter tanggal peminjaman
        if ($request->filled('tanggal')) {
            $query->whereHas('transaction', fn($q) =>
                $q->whereDate('tanggal_peminjaman', $request->tanggal)
            );
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()->paginate(10)->withQueryString();

        return view('siswa.laporan_kehilangan', compact('reports'));
    }

    public function edit($id)
    {
        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('siswa.laporan-kehilangan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_ganti' => 'required|date',
            'keterangan' => 'required|string|max:500'
        ]);

        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($laporan->status !== 'belum_dikembalikan') {
            return back()->with('error', 'Laporan yang sudah diproses tidak bisa diubah');
        }

        $laporan->update([
            'tanggal_ganti' => $request->tanggal_ganti,
            'keterangan' => $request->keterangan
        ]);

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Laporan kehilangan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($laporan->status !== 'pending' && $laporan->status !== 'belum_dikembalikan') {
            return back()->with('error', 'Hanya laporan yang belum diproses bisa dihapus');
        }

        // Reset status transaksi ke belum_dikembalikan jika laporan dihapus
        if ($laporan->transaction) {
            $laporan->transaction->update(['status' => 'belum_dikembalikan']);
        }

        $laporan->delete();

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Laporan kehilangan berhasil dihapus');
    }

    /**
     * Anggota menandai laporan sebagai kembalikan (mengembalikan buku).
     */
    public function kembalikan($id)
    {
        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($laporan->status !== 'pending') {
            return back()->with('error', 'Laporan tidak dapat diproses');
        }

        $laporan->update(['status' => 'menunggu_konfirmasi']);

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Pengajuan penggantian buku berhasil dikirim, menunggu konfirmasi admin');
    }
}