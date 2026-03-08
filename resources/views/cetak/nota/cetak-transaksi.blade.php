<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nota - {{ ucfirst($jenis ?? 'Transaksi') }}</title>

    @if($jenis === 'peminjaman')
        <link rel="stylesheet" href="{{ asset('css/cetak/cetak-peminjaman.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/cetak/cetak-pengembalian.css') }}">
    @endif
</head>

<body @if($jenis === 'pengembalian') onload="window.print()" @endif>

@if($jenis === 'peminjaman')

    <!-- ================================================== -->
    <!--          CETAK PEMINJAMAN (layout card)           -->
    <!-- ================================================== -->
    <div class="card">
        <!-- HEADER -->
        <div class="header">
            <div class="logo">
                <img src="{{ asset('img/icon-cetak.png') }}" alt="Logo Perpustakaan">
            </div>
        </div>

        <!-- SUCCESS -->
        <div class="success">
            <div class="check" style="font-family: DejaVu Sans; font-size:70px; color:#fff;">
                &#10004;
            </div>
            <h2>Selamat! Peminjaman Buku Berhasil</h2>
            <p>Anda telah berhasil meminjam buku</p>
        </div>

        <hr class="dashed">

        <!-- DATA PEMINJAM -->
        <div class="content">
            <div class="row">
                <span>Nama Peminjam</span>
                <span>{{ $transaction->user->name ?? '-' }}</span>
            </div>
            <div class="row">
                <span>NIS / NISN</span>
                <span>{{ $transaction->user->nis_nisn ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Kelas / Jurusan</span>
                <span>{{ $transaction->user->kelas ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Judul Buku</span>
                <span>{{ $transaction->book->judul ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Kode Buku</span>
                <span>{{ $transaction->book->kode_buku ?? '-' }}</span>
            </div>
        </div>

        <hr class="dashed">

        <!-- DATA TANGGAL -->
        <div class="content">
            <div class="row">
                <span>Tanggal Peminjaman</span>
                <span>{{ optional($transaction->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Batas Pengembalian</span>
                <span>{{ optional($transaction->tanggal_jatuh_tempo)->format('d/m/Y') ?? '-' }}</span>
            </div>
            <div class="row">
                <span>Nama Perpustakaan</span>
                <span>PERPUSTAKAAN SMKN 4 BOJONEGORO</span>
            </div>
            <div class="row">
                <span>Info batas pinjam berikutnya</span>
                <span>
                    Peminjam dapat meminjam kembali mulai:
                    {{ optional($transaction->tanggal_jatuh_tempo)->format('d/m/Y') ?? 'Sesuai ketentuan' }}
                </span>
            </div>
        </div>

        <!-- NOTE -->
        <div class="note">
            <img src="{{ asset('img/icon-warna.png') }}" alt="Icon">
            <p>
                Di antara rak-rak buku ini, kamu mungkin hanya duduk membaca.<br>
                Tapi sebenarnya kamu sedang membangun versi terbaik dari dirimu sendiri.
            </p>
        </div>
    </div>

@else

    <!-- ================================================== -->
    <!--          CETAK PENGEMBALIAN (layout paper)        -->
    <!-- ================================================== -->
    <div class="wrapper">
        <div class="paper">

            <!-- HEADER -->
            <div class="header">
                <img src="{{ asset('img/icon-warna.png') }}" alt="Logo">
                <div class="header-text">
                    <h1>PERPUSTAKAAN</h1>
                    <p>SMKN 4 BOJONEGORO</p>
                </div>
            </div>

            <div class="line"></div>

            <!-- DATA PEMINJAM -->
            <div class="row">
                <div class="label">Nama Peminjam</div>
                <div class="value name">{{ $transaction->user->name ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="label">NIS/NISN</div>
                <div class="value">{{ $transaction->user->nis_nisn ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="label">Kelas / Jurusan</div>
                <div class="value">{{ $transaction->user->kelas ?? '-' }}</div>
            </div>

            <!-- RINCIAN BUKU -->
            <div class="section">Rincian Buku</div>
            <div class="row">
                <div class="label">Judul Buku</div>
                <div class="value">{{ $transaction->book->judul ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="label">Kode Buku</div>
                <div class="value">{{ $transaction->book->kode_buku ?? '-' }}</div>
            </div>

            <!-- DATA PEMINJAMAN -->
            <div class="section">Data Peminjaman Awal</div>
            <div class="row">
                <div class="label">Tanggal Pinjam</div>
                <div class="value">
                    {{ optional($transaction->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}
                </div>
            </div>
            <div class="row">
                <div class="label">Batas Pengembalian</div>
                <div class="value">
                    {{ optional($transaction->tanggal_jatuh_tempo)->format('d/m/Y') ?? '-' }}
                </div>
            </div>

            <!-- PENGEMBALIAN -->
            <div class="section">Pengembalian</div>
            <div class="row">
                <div class="label">Tanggal Dikembalikan</div>
                <div class="value">
                    {{ optional($transaction->tanggal_pengembalian)->format('d/m/Y') ?? 'Belum dikembalikan' }}
                </div>
            </div>
            <div class="row">
                <div class="label">Status</div>
                <div class="value">
                    {{ ucfirst(str_replace('_', ' ', $transaction->status ?? '-')) }}
                </div>
            </div>

        </div>
    </div>

@endif

</body>
</html>