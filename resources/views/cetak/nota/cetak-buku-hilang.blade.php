{{-- resources/views/cetak/nota/cetak-buku-hilang.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nota Penggantian Buku Hilang</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }

        .header { background-color: #7f1d1d; color: white; padding: 18px 24px 14px; }
        .header-accent { height: 5px; background-color: #f5a623; }
        .logo-text { font-size: 17px; font-weight: bold; }
        .logo-accent { color: #f5a623; }
        .logo-sub { font-size: 9px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1.5px; margin-top: 2px; margin-bottom: 14px; }
        .header-bottom { display: table; width: 100%; }
        .header-title { display: table-cell; vertical-align: bottom; }
        .header-title h1 { font-size: 20px; font-weight: bold; line-height: 1.1; }
        .header-meta { display: table-cell; vertical-align: bottom; text-align: right; font-size: 9px; color: rgba(255,255,255,0.7); line-height: 1.8; }

        .body { padding: 16px 24px; }

        .status-badge { display: inline-block; background-color: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 20px; padding: 4px 14px; font-size: 10px; font-weight: bold; margin-bottom: 14px; }

        .section-label { font-size: 9px; font-weight: bold; color: #aaa; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 8px; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-label { color: #888; font-size: 10px; width: 38%; }
        .info-sep { width: 5%; color: #bbb; }
        .info-value { font-size: 11px; font-weight: bold; color: #1a1a2e; }

        .card-box { background-color: #f9f8f6; border-left: 3px solid #7f1d1d; padding: 7px 10px; border-radius: 4px; margin-bottom: 7px; }
        .card-box-label { font-size: 9px; color: #aaa; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px; }
        .card-box-value { font-size: 11px; font-weight: bold; color: #1a1a2e; }

        .two-col { display: table; width: 100%; margin-bottom: 7px; }
        .col-left { display: table-cell; width: 50%; padding-right: 5px; vertical-align: top; }
        .col-right { display: table-cell; width: 50%; padding-left: 5px; vertical-align: top; }

        .divider { border: none; border-top: 1.5px dashed #e0ddd8; margin: 12px 0; }

        .footer-row { display: table; width: 100%; }
        .footer-left { display: table-cell; vertical-align: middle; font-size: 10px; color: #aaa; line-height: 1.7; }
        .footer-right { display: table-cell; vertical-align: middle; text-align: right; }
        .stamp { display: inline-block; width: 65px; height: 65px; border: 2.5px solid #7f1d1d; border-radius: 50%; text-align: center; padding-top: 12px; }
        .stamp-top { font-size: 8px; font-weight: bold; text-transform: uppercase; color: #7f1d1d; display: block; }
        .stamp-check { font-size: 20px; color: #1a7a4a; display: block; line-height: 1; }

        .bottom-bar { background-color: #f9f8f6; border-top: 1px solid #ede9e4; padding: 8px 24px; text-align: center; font-size: 10px; color: #bbb; margin-top: 6px; }

        .alert-box { background-color: #fef2f2; border: 1px solid #fca5a5; border-radius: 6px; padding: 8px 12px; margin-bottom: 14px; font-size: 10px; color: #991b1b; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="logo-text">EduTech <span class="logo-accent">Library</span></div>
        <div class="logo-sub">Sistem Perpustakaan Digital</div>
        <div class="header-bottom">
            <div class="header-title">
                <h1>Nota Penggantian<br>Buku Hilang</h1>
            </div>
            <div class="header-meta">
                No. NOTA-HILANG-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}<br>
                Dicetak: {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </div>
    <div class="header-accent"></div>

    <div class="body">

        <div class="status-badge">&#10007; Buku Hilang &bull; Sudah Diganti</div>

        {{-- KETERANGAN KEHILANGAN --}}
        <div class="alert-box">
            <strong>Keterangan Kehilangan:</strong> {{ $report->keterangan }}
        </div>

        {{-- INFORMASI PEMINJAM --}}
        <div class="section-label">Informasi Peminjam</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Nama</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $report->user->name }}</td>
            </tr>
            <tr>
                <td class="info-label">Username</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $report->user->username }}</td>
            </tr>
            @if($report->user->nis_nisn)
            <tr>
                <td class="info-label">NIS / NISN</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $report->user->nis_nisn }}</td>
            </tr>
            @endif
            @if($report->user->kelas)
            <tr>
                <td class="info-label">Kelas</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $report->user->kelas }}</td>
            </tr>
            @endif
        </table>

        {{-- DETAIL BUKU --}}
        <div class="section-label">Detail Buku</div>

        <div class="card-box">
            <div class="card-box-label">Judul Buku</div>
            <div class="card-box-value">{{ $report->transaction->book->judul ?? '-' }}</div>
        </div>

        <div class="two-col">
            <div class="col-left">
                <div class="card-box">
                    <div class="card-box-label">Kode Buku</div>
                    <div class="card-box-value">{{ $report->transaction->book->kode_buku ?? '-' }}</div>
                </div>
            </div>
            <div class="col-right">
                <div class="card-box">
                    <div class="card-box-label">Pengarang</div>
                    <div class="card-box-value">{{ $report->transaction->book->pengarang ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="two-col">
            <div class="col-left">
                <div class="card-box">
                    <div class="card-box-label">Tanggal Pinjam</div>
                    <div class="card-box-value">
                        {{ optional($report->transaction->tanggal_peminjaman)->translatedFormat('d F Y') ?? '-' }}
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="card-box">
                    <div class="card-box-label">Tanggal Penggantian</div>
                    <div class="card-box-value">
                        {{ optional($report->tanggal_ganti)->translatedFormat('d F Y') ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        <hr class="divider">

        <div class="footer-row">
            <div class="footer-left">
                Dokumen ini sah sebagai bukti<br>
                penggantian buku yang hilang.<br>
                <span style="color: #ccc; font-size: 9px;">
                    NOTA-HILANG-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>
            <div class="footer-right">
                <div class="stamp">
                    <span class="stamp-top">Verified</span>
                    <span class="stamp-check">&#10003;</span>
                </div>
            </div>
        </div>

    </div>

    <div class="bottom-bar">
        EduTech Library &bull; Terima kasih atas tanggung jawab dalam penggantian buku
    </div>

</body>
</html>