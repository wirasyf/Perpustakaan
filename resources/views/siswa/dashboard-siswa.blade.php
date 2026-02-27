@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/dashboard-siswa.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush    

@section('content')

<main class="main-content">

    <!-- HERO SECTION -->
    <div class="hero-card">
        <div class="hero-left">
            @php
                $firstName = explode(' ', auth()->user()->name ?? 'Siswa')[0];
            @endphp
            <h1 class="hero-greeting">Hola, {{ $firstName }}!</h1>
            <p class="hero-subtext">Selamat Datang Di Edutech Library</p>
        </div>
        <img src="{{ asset('img/hero.png') }}" alt="Welcome Illustration" class="hero-img">
    </div>

    <!-- STATS (5 CARDS) -->
    <section class="stats-grid">
        
        <div class="stat-item">
            <div class="stat-icon-wrapper icon-yellow">
                <i class="fa fa-book-open"></i>
            </div>
            <div class="stat-title">Perpanjangan Buku</div>
            <div class="stat-number">0</div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-wrapper icon-purple">
                <i class="fa fa-book-bookmark"></i>
            </div>
            <div class="stat-title">Sedang dipinjam</div>
            <div class="stat-number">{{ $totalDipinjam ?? 0 }}</div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-wrapper icon-red">
                <i class="fa fa-book-skull"></i>
            </div>
            <div class="stat-title">Buku Hilang</div>
            <div class="stat-number">{{ $totalBukuHilang ?? 0 }}</div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-wrapper icon-purple">
                <i class="fa fa-users"></i>
            </div>
            <div class="stat-title">Kunjungan</div>
            <div class="stat-number">{{ $totalKunjungan ?? 0 }}</div>
        </div>

        <div class="stat-item">
            <div class="stat-icon-wrapper icon-red">
                <i class="fa fa-clock-rotate-left"></i>
            </div>
            <div class="stat-title">Terlambat</div>
            <div class="stat-number">{{ $totalTerlambat ?? 0 }}</div>
        </div>

    </section>

    <!-- MAIN CONTENT (2 COLUMNS) -->
    <section class="content-grid">
        
        <!-- TRANSAKSI TABLE -->
        <div class="table-card">
            <h3>Riwayat Transaksi</h3>
            <div style="overflow-x:auto;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Judul Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Mengganti</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPeminjaman as $trx)
                        <tr>
                            <td>{{ $trx->book->judul ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $statusClass = 'status-warning';
                                    if(strtolower($trx->status) == 'sudah dikembalikan' || strtolower($trx->status) == 'dikembalikan') $statusClass = 'status-success';
                                    if(strtolower($trx->status) == 'belum dikembalikan' || strtolower($trx->status) == 'dipinjam') $statusClass = 'status-danger';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $trx->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 20px;">
                                Belum ada riwayat transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ACTION CARDS -->
        <div class="action-cards">
            
            <!-- ABSEN CARD -->
            <div class="action-card">
                <h4>Lakukan Absensi Kunjungan Perpustakaan</h4>
                <img src="{{ asset('img/hero.png') }}" alt="Absen" class="action-img">
                <button class="btn-action" id="btnHadir" {{ ($kunjunganHariIni ?? false) ? 'disabled' : '' }}>
                    @if($kunjunganHariIni ?? false)
                        Sudah Hadir
                    @else
                        Absen Kunjungan
                    @endif
                </button>
            </div>

            <!-- CETAK KARTU CARD -->
            <div class="action-card">
                <h4>Cetak Kartu Anggota</h4>
                <img src="{{ asset('img/hero.png') }}" alt="Cetak Kartu" class="action-img">
                <button type="button" class="btn-action" id="btnCetakKartu" onclick="downloadKartuSiswa()">
                    Cetak Kartu Anggota
                </button>
            </div>

        </div>

    </section>

</main>

<script>
// Absensi Kunjungan
const btnHadir = document.getElementById('btnHadir');
if(btnHadir){
    btnHadir.addEventListener('click', async () => {
        try {
            const response = await fetch("{{ route('checkin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });

            const data = await response.json();

            if(response.ok){
                btnHadir.innerHTML = 'Sudah Hadir';
                btnHadir.disabled = true;
                alert(data.message || 'Berhasil check-in');
            } else {
                alert(data.message || 'Gagal check-in');
            }
        } catch (error) {
            alert("Terjadi error");
        }
    });
}

// Download Kartu
function downloadKartuSiswa() {
    const btn = document.getElementById('btnCetakKartu');
    const originalHTML = btn ? btn.innerHTML : '';
    
    if (btn) {
        btn.innerHTML = 'Mengunduh...';
        btn.disabled = true;
    }

    fetch("{{ route('kartu.download') }}")
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengunduh kartu');
            const cd = response.headers.get('Content-Disposition');
            let filename = 'kartu-anggota.pdf';
            if (cd) {
                const match = cd.match(/filename[^;=\n]*=(['"]?)([^'"\n]*?)\1(;|$)/);
                if (match) filename = match[2];
            }
            return response.blob().then(blob => ({ blob, filename }));
        })
        .then(({ blob, filename }) => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        })
        .catch(error => {
            alert(error.message || 'Terjadi kesalahan saat mengunduh kartu.');
        })
        .finally(() => {
            if (btn) {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        });
}
</script>

@endsection