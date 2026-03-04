<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Daftar Pengunjung</title>
<link rel="stylesheet" href="{{ public_path('css/cetak/cetak-kunjungan.css') }}">
</head>
<body>
    <div class="paper">
        <table width="100%" style="border:0;">
            <tr>
                <td width="15%" style="border:0;">
                    <img src="{{ public_path('img/logo_smk4.png') }}" width="80">
                </td>
                <td width="85%" style="border:0; text-align:center;">
                    <h2 style="margin:0;">SMK NEGERI 4 BOJONEGORO</h2>
                    <h3 style="margin:0;">PERPUSTAKAAN</h3>
                    <p style="margin:2px 0;">
                        JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur<br>
                        Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id
                    </p>
                </td>
            </tr>
        </table>
        <hr>
        <div class="info">
            <p><strong>Hal : Laporan Daftar Pengunjung Perpustakaan</strong></p>
            <p>Periode : 
                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }} 
                s/d 
                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}
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
