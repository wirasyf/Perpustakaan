{{-- resources/views/cetak/nota/cetak-peminjaman.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nota Peminjaman Buku</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }

        .header { background-color: #1e3a5f; color: white; padding: 18px 24px 14px; }
        .header-accent { height: 5px; background-color: #f5a623; }
        .logo-text { font-size: 17px; font-weight: bold; }
        .logo-accent { color: #f5a623; }
        .logo-sub { font-size: 9px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1.5px; margin-top: 2px; margin-bottom: 14px; }
        .header-bottom { display: table; width: 100%; }
        .header-title { display: table-cell; vertical-align: bottom; }
        .header-title h1 { font-size: 20px; font-weight: bold; line-height: 1.1; }
        .header-meta { display: table-cell; vertical-align: bottom; text-align: right; font-size: 9px; color: rgba(255,255,255,0.7); line-height: 1.8; }

        .body { padding: 16px 24px; }

        .status-badge { display: inline-block; background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; border-radius: 20px; padding: 4px 14px; font-size: 10px; font-weight: bold; margin-bottom: 14px; }

        .section-label { font-size: 9px; font-weight: bold; color: #aaa; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 8px; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-label { color: #888; font-size: 10px; width: 38%; }
        .info-sep { width: 5%; color: #bbb; }
        .info-value { font-size: 11px; font-weight: bold; color: #1a1a2e; }

        .card-box { background-color: #f0f4ff; border-left: 3px solid #1e3a5f; padding: 7px 10px; border-radius: 4px; margin-bottom: 7px; }
        .card-box-label { font-size: 9px; color: #aaa; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px; }
        .card-box-value { font-size: 11px; font-weight: bold; color: #1a1a2e; }

        .two-col { display: table; width: 100%; margin-bottom: 7px; }
        .col-left { display: table-cell; width: 50%; padding-right: 5px; vertical-align: top; }
        .col-right { display: table-cell; width: 50%; padding-left: 5px; vertical-align: top; }

        .divider { border: none; border-top: 1.5px dashed #e0ddd8; margin: 12px 0; }

        .info-box { background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 8px 12px; margin-bottom: 14px; font-size: 10px; color: #92400e; }

        .footer-row { display: table; width: 100%; }
        .footer-left { display: table-cell; vertical-align: middle; font-size: 10px; color: #aaa; line-height: 1.7; }
        .footer-right { display: table-cell; vertical-align: middle; text-align: right; }
        .stamp { display: inline-block; width: 65px; height: 65px; border: 2.5px solid #1e3a5f; border-radius: 50%; text-align: center; padding-top: 12px; }
        .stamp-top { font-size: 8px; font-weight: bold; text-transform: uppercase; color: #1e3a5f; display: block; }
        .stamp-check { font-size: 20px; color: #1a7a4a; display: block; line-height: 1; }

        .bottom-bar { background-color: #f9f8f6; border-top: 1px solid #ede9e4; padding: 8px 24px; text-align: center; font-size: 10px; color: #bbb; margin-top: 6px; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="logo-text">EduTech <span class="logo-accent">Library</span></div>
        <div class="logo-sub">Sistem Perpustakaan Digital</div>
        <div class="header-bottom">
            <div class="header-title">
                <h1>Nota Peminjaman<br>Buku</h1>
            </div>
            <div class="header-meta">
                No. NOTA-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}<br>
                Dicetak: {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </div>
    <div class="header-accent"></div>

    <div class="body">

        <div class="status-badge">&#10003; Peminjaman Buku Berhasil</div>

        {{-- INFORMASI PEMINJAM --}}
        <div class="section-label">Informasi Peminjam</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Nama</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $transaksi->user->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Username</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $transaksi->user->username ?? '-' }}</td>
            </tr>
            @if($transaksi->user->nis_nisn)
            <tr>
                <td class="info-label">NIS / NISN</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $transaksi->user->nis_nisn }}</td>
            </tr>
            @endif
            @if($transaksi->user->kelas)
            <tr>
                <td class="info-label">Kelas</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $transaksi->user->kelas }}</td>
            </tr>
            @endif
        </table>

        {{-- DETAIL BUKU --}}
        <div class="section-label">Detail Buku</div>

        <div class="card-box">
            <div class="card-box-label">Judul Buku</div>
            <div class="card-box-value">{{ $transaksi->book->judul ?? '-' }}</div>
        </div>

        <div class="two-col">
            <div class="col-left">
                <div class="card-box">
                    <div class="card-box-label">Kode Buku</div>
                    <div class="card-box-value">{{ $transaksi->book->kode_buku ?? '-' }}</div>
                </div>
            </div>
            <div class="col-right">
                <div class="card-box">
                    <div class="card-box-label">Pengarang</div>
                    <div class="card-box-value">{{ $transaksi->book->pengarang ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- TANGGAL --}}
        <div class="two-col">
            <div class="col-left">
                <div class="card-box">
                    <div class="card-box-label">Tanggal Pinjam</div>
                    <div class="card-box-value">
                        {{ optional($transaksi->tanggal_peminjaman)->translatedFormat('d F Y') ?? '-' }}
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="card-box">
                    <div class="card-box-label">Batas Pengembalian</div>
                    <div class="card-box-value">
                        {{ optional($transaksi->tanggal_jatuh_tempo)->translatedFormat('d F Y') ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- INFO PENTING --}}
        <div class="info-box">
            &#9888; Harap kembalikan buku sebelum batas pengembalian untuk menghindari keterlambatan pengembalian buku.
        </div>

        <hr class="divider">

        <div class="footer-row">
            <div class="footer-left">
                Dokumen ini sah sebagai bukti<br>
                peminjaman buku perpustakaan.<br>
                <span style="color: #ccc; font-size: 9px;">
                    NOTA-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}
                </span>
            </div>
            <div class="footer-right">
                <div class="stamp">
                    <span class="stamp-top">Dipinjam</span>
                    <span class="stamp-check">&#10003;</span>
                </div>
            </div>
        </div>

    </div>

    <div class="bottom-bar">
        EduTech Library &bull; Terima kasih telah menggunakan layanan perpustakaan kami
    </div>

</body>
</html>