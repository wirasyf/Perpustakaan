<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Kehilangan</title>
    <link rel="stylesheet" href="{{ asset('css/cetak/cetak-kehilangan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <!-- KERTAS -->
    <div class="paper">

        <!-- KOP -->
        <div class="kop">
            <img src="{{ asset('img/logo_smk4.png') }}" class="logo">
            <div class="kop-text">
                <h2>SMK NEGERI 4 BOJONEGORO</h2>
                <h3>PERPUSTAKAAN</h3>
                <p>
                    JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur<br>
                    Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id
                </p>
            </div>
        </div>

        <hr>

        <div class="info">
            <p>Hal : Laporan Transaksi Perpustakaan</p>
            <p>Periode : 01 Januari s/d 31 Januari 2026</p>
        </div>

        <!-- TABEL -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Kelas</th>
                    <th>Judul Buku</th>
                    <th>Keterangan</th>
                    <th>Tanggal Datang</th>
                    <th>Setatus</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $r->user->name ?? $r->transaction->user->name ?? '-' }}</td>
                <td>{{ $r->user->kelas ?? $r->transaction->user->kelas ?? '-' }}</td>
                <td>{{ $r->transaction->book->judul ?? '-' }}</td>
                <td>{{ $r->keterangan ?? $r->description ?? '-' }}</td> {{-- ← keterangan --}}
                <td>{{ optional($r->created_at)->format('d/m/Y') }}</td>
                <td>
                    @if($r->status == 'sudah_dikembalikan')
                        <span class="badge-done">Sudah Diganti</span>
                    @else
                        <span class="badge-pending">Belum Diganti</span>
                    @endif
                </td>
                <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $r->user->name ?? $r->transaction->user->name ?? '-' }}</td>
                <td>{{ $r->user->kelas ?? $r->transaction->user->kelas ?? '-' }}</td>
                <td>{{ $r->transaction->book->judul ?? '-' }}</td>
                <td>{{ $r->keterangan ?? $r->description ?? '-' }}</td> {{-- ← keterangan --}}
                <td>{{ optional($r->created_at)->format('d/m/Y') }}</td>
                <td>
                    @if($r->status == 'sudah_dikembalikan')
                        <span class="badge-done">Sudah Diganti</span>
                    @else
                        <span class="badge-pending">Belum Diganti</span>
                    @endif
                </td>
                </tr>
            </tbody>
        </table>

        <div class="paper-footer">
            <span>dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>halaman 1 dari 3</span>
        </div>
 </div>

</div>

</body>