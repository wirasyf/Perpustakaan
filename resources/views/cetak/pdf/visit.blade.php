<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Daftar Pengunjung</title>
<link rel="stylesheet" href="{{ public_path('css/cetak/cetak-kunjungan.css') }}">
</head>
<body>
    <div class="paper">
        <div class="kop">
            <img src="{{ public_path('img/logo_smk4.png') }}" class="logo">
            <div class="kop-text">
                <h2>SMK NEGERI 4 BOJONEGORO</h2>
                <h3>PERPUSTAKAAN</h3>
                <p>
                    JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur<br>
                    Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id
                </p>
            </div>
        </div>
        <div class="info">
            <p><strong>Hal : Laporan Daftar Pengunjung Perpustakaan</strong></p>
            <p>Periode : 
                @if($hari)
                    {{ \Carbon\Carbon::parse($hari)->format('d/m/Y') }}
                @elseif($bulan || $tahun)
                    @if($bulan)
                        {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }}
                    @endif
                    @if($tahun)
                        {{ $tahun }}
                    @endif
                @else
                    Semua Data
                @endif
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Kelas</th>
                    <th>Tanggal Datang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $index => $v)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $v->user->name ?? '-' }}</td>
                    <td>{{ $v->user->kelas ?? '-' }}</td>
                    <td>{{ $v->tanggal_datang??'-' }}</td>
                </tr>
                            @empty
                <tr><td colspan="6" style="text-align:center;">Tidak ada data kunjungan</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="paper-footer">
            <span>Dicetak oleh Perpustakaan SMKN 4 Bojonegoro</span>
            <span>{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>
</body>
</html>
