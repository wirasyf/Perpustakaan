<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehilangan Buku</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", sans-serif; }
        body { background: #fff; }
        .paper { padding: 20px; }

        /* KOP */
        .kop { display: flex; align-items: center; gap: 15px; margin-bottom: 10px; }
        .logo { width: 70px; }
        .kop-text { text-align: center; flex: 1; }
        .kop-text h2 { font-size: 16px; font-weight: 700; }
        .kop-text h3 { font-size: 13px; font-weight: 600; }
        .kop-text p  { font-size: 11px; color: #555; }
        hr { border: none; border-top: 2px solid #1F4E79; margin: 10px 0; }

        /* INFO */
        .info { font-size: 12px; margin-bottom: 12px; }
        .info p { margin-bottom: 2px; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        thead { background: #1F4E79; color: #fff; }
        th { padding: 8px 6px; text-align: center; border: 1px solid #cfcfcf; color: #fff; } /* ← tambah color: #fff */
        td { padding: 6px; border: 1px solid #cfcfcf; vertical-align: middle; }
        td:nth-child(1), td:nth-child(3), td:nth-child(6), td:nth-child(7) { text-align: center; }
        tr:nth-child(even) { background: #DBEAFE; }
        tr:nth-child(odd)  { background: #F9F9F9; }

        /* BADGE */
        .badge-done    { background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 20px; font-size: 10px; }
            .badge-pending { background: #DBEAFE; color: #1F4E79; padding: 2px 8px; border-radius: 20px; font-size: 10px; }
        /* FOOTER */
        .paper-footer { display: flex; justify-content: space-between; font-size: 10px; margin-top: 12px; color: #555; }
    </style>
</head>
<body>
<div class="paper">

    <div class="kop">
        <img src="{{ public_path('img/logo_smk4.png') }}" class="logo">
        <div class="kop-text">
            <h2>SMK NEGERI 4 BOJONEGORO</h2>
            <h3>PERPUSTAKAAN</h3>
            <p>JL. RAYA SURABAYA BOJONEGORO, Sukowati, Kec. Kapas, Kab. Bojonegoro, Jawa Timur<br>
            Telp. (0353) 892418 | Email : smkn4bojonegoro@yahoo.co.id</p>
        </div>
    </div>

    <hr>

    <div class="info">
        <p><strong>Hal :</strong> Laporan Kehilangan Buku Perpustakaan</p>
        <p><strong>Periode :</strong>
            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }}
            s/d
            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}
        </p>
        @if(request('status') && request('status') !== 'semua')
        <p><strong>Status :</strong>
            {{ request('status') == 'sudah_dikembalikan' ? 'Sudah Diganti' : 'Belum Diganti' }}
        </p>
        @endif
    </div>

    <table>
<thead>
    <tr style="background-color: #1F4E79;">
        <th style="color: #ffffff; padding: 8px 6px; text-align: center; border: 1px solid #cfcfcf;">No</th>
        <th style="color: #ffffff; padding: 8px 6px; text-align: center; border: 1px solid #cfcfcf;">Nama Anggota</th>
        <th style="color: #ffffff; padding: 8px 6px; text-align: center; border: 1px solid #cfcfcf;">Kelas</th>
        <th style="color: #ffffff; padding: 8px 6px; text-align: center; border: 1px solid #cfcfcf;">Judul Buku</th>
        <th style="color: #ffffff; padding: 8px 6px; text-align: center; border: 1px solid #cfcfcf;">Keterangan</th>
        <th style="color: #ffffff; padding: 8px 6px; text-align: center; border: 1px solid #cfcfcf;">Tanggal Laporan</th>
        <th style="color: #ffffff; padding: 8px 6px; text-align: center; border: 1px solid #cfcfcf;">Status</th>
    </tr>
</thead>
        <tbody>
            @forelse($reports as $index => $r)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $r->user->name ?? $r->transaction->user->name ?? '-' }}</td>
                <td>{{ $r->user->kelas ?? $r->transaction->user->kelas ?? '-' }}</td>
                <td>{{ $r->transaction->book->judul ?? '-' }}</td>
                <td>{{ $r->keterangan ?? '-' }}</td>   {{-- ← keterangan --}}
                <td>{{ optional($r->created_at)->format('d/m/Y') }}</td>
                <td>
                    @if($r->status == 'sudah_dikembalikan')
                        <span class="badge-done">Sudah Diganti</span>
                    @else
                        <span class="badge-pending">Belum Diganti</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;">Tidak ada data kehilangan</td></tr>
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