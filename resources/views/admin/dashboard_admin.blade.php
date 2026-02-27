@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard_admin.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
<div class="dashboard-container">

    <!-- TOP METRICS -->
    <section class="metrics-grid">
        <!-- Card 1: Jumlah Buku -->
        <div class="metric-card">
            <div class="metric-icon icon-yellow">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <div class="metric-title">Jumlah Buku</div>
            <div class="metric-value">{{ number_format($totalBook) }}</div>
        </div>

        <!-- Card 2: Buku dipinjam -->
        <div class="metric-card">
            <div class="metric-icon icon-purple-light">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div class="metric-title">Buku dipinjam</div>
            <div class="metric-value">{{ number_format($totalBorrow) }}</div>
        </div>

        <!-- Card 3: Belum dikembalikan -->
        <div class="metric-card">
            <div class="metric-icon icon-blue-light">
                <i class="fa-solid fa-book-open-reader"></i>
            </div>
            <div class="metric-title">Belum dikembalikan</div>
            <div class="metric-value">{{ number_format($totalBelumDikembalikan) }}</div>
        </div>

        <!-- Card 4: Buku Hilang -->
        <div class="metric-card">
            <div class="metric-icon icon-red-light">
                <i class="fa-solid fa-book-medical"></i> <!-- Used generic icon as placeholder for slashed book -->
            </div>
            <div class="metric-title">Buku Hilang</div>
            <div class="metric-value">{{ number_format($totalLostBooks) }}</div>
        </div>

        <!-- Card 5: Kunjungan -->
        <div class="metric-card">
            <div class="metric-icon icon-purple-faded">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="metric-title">Kunjungan</div>
            <div class="metric-value">{{ number_format($totalVisit) }}</div>
        </div>
    </section>

    <!-- MAIN GRID -->
    <section class="main-grid">

        <!-- LEFT COLUMN: Pengunjung Hari ini -->
        <div class="panel list-panel">
            <h3 class="panel-title">Pengunjung Hari ini</h3>
            <table class="visitor-table">
                <thead>
                    <tr>
                        <th>Nama Pengunjung</th>
                        <th>Transaksi</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayVisit as $visit)
                    <tr>
                        <td>
                            <div class="visitor-info">
                                @if($visit->user && $visit->user->profile_photo)
                                    <img src="{{ asset($visit->user->profile_photo) }}" class="visitor-avatar" alt="Avatar">
                                @else
                                    <div class="visitor-avatar">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                                <span class="nama-text">{{ $visit->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="transaksi-text">{{ $visit->transaction->jenis_transaksi ?? 'Pinjam Buku' }}</span>
                        </td>
                        <td>
                            <span class="kelas-text">{{ $visit->user->kelas ?? '-' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center">Tidak ada pengunjung hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="right-column">

            <!-- Laporan Kehilangan (Mini Table Preview) -->
            <div class="panel">
                <h3 class="panel-title" style="margin-bottom: 5px;">Laporan Kehilangan</h3>
                <div style="overflow-x: auto;">
                    <table class="laporan-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Anggota</th>
                                <th>Judul Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Mengganti</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestReport as $index => $report)
                            <tr>
                                <td>{{ $index + 1 }}.</td>
                                <td>{{ Str::limit($report->transaction->user->name ?? '-', 15) }}</td>
                                <td>{{ Str::limit($report->transaction->book->judul ?? '-', 15) }}</td>
                                <td>{{ optional($report->transaction->tanggal_peminjaman)->format('d/m/Y') }}</td>
                                <td>{{ optional($report->tanggal_ganti)->format('d/m/Y') }}</td>
                                <td>
                                    @if($report->status == 'sudah_dikembalikan')
                                        <span class="badge-sudah">Sudah dikembalikan</span>
                                    @else
                                        <span class="badge-belum">Belum dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Akun Belum di Verifikasi Card -->
            <div class="unverified-card">
                <div class="unverified-number">{{ $unverifiedUsers }}</div>
                <div class="unverified-text">
                    Akun Belum<br>di Verifikasi
                </div>
            </div>

        </div>

    </section>

</div>
@endsection