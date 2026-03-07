{{-- resources/views/pengembalian/nota_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nota Pengembalian - {{ $no_nota }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            background: #ffffff;
        }

        .header {
            background-color: #1e3a5f;
            color: white;
            padding: 22px 28px 18px;
        }
        .header-accent {
            height: 5px;
            background-color: #f5a623;
        }
        .logo-text {
            font-size: 17px;
            font-weight: bold;
        }
        .logo-accent { color: #f5a623; }
        .logo-sub {
            font-size: 9px;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 2px;
            margin-bottom: 16px;
        }
        .header-bottom { display: table; width: 100%; }
        .header-title  { display: table-cell; vertical-align: bottom; }
        .header-title h1 { font-size: 22px; font-weight: bold; line-height: 1.1; }
        .header-meta {
            display: table-cell;
            vertical-align: bottom;
            text-align: right;
            font-size: 9px;
            color: rgba(255,255,255,0.7);
            line-height: 1.8;
        }

        .body { padding: 14px 22px; }

        .status-badge {
            display: inline-block;
            background-color: #edfaf4;
            color: #1a7a4a;
            border: 1px solid #a3e6c3;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .section-label {
            font-size: 9px;
            font-weight: bold;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 6px;
        }

        /* Info table (peminjam) */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .info-label  { color: #888; font-size: 10px; width: 38%; }
        .info-sep    { width: 5%; color: #bbb; }
        .info-value  { font-size: 10px; font-weight: bold; color: #1a1a2e; }

        /* Card box */
        .card-box {
            background-color: #f9f8f6;
            border-left: 3px solid #1e3a5f;
            padding: 6px 10px;
            border-radius: 4px;
            margin-bottom: 6px;
        }
        .card-box-label {
            font-size: 9px;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 3px;
        }
        .card-box-value { font-size: 11px; font-weight: bold; color: #1a1a2e; }

        /* Two column */
        .two-col    { display: table; width: 100%; margin-bottom: 6px; }
        .col-left   { display: table-cell; width: 50%; padding-right: 6px; vertical-align: top; }
        .col-right  { display: table-cell; width: 50%; padding-left: 6px; vertical-align: top; }

        .divider {
            border: none;
            border-top: 1.5px dashed #e0ddd8;
            margin: 10px 0;
        }

        /* Footer */
        .footer-row  { display: table; width: 100%; }
        .footer-left {
            display: table-cell;
            vertical-align: middle;
            font-size: 10px;
            color: #aaa;
            line-height: 1.7;
        }
        .footer-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }
        .stamp {
            display: inline-block;
            width: 70px; height: 70px;
            border: 2.5px solid #1e3a5f;
            border-radius: 50%;
            text-align: center;
            padding-top: 14px;
        }
        .stamp-top {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1e3a5f;
            letter-spacing: 0.5px;
            display: block;
        }
        .stamp-check { font-size: 22px; color: #1a7a4a; display: block; line-height: 1; }

        .bottom-bar {
            background-color: #f9f8f6;
            border-top: 1px solid #ede9e4;
            padding: 10px 28px;
            text-align: center;
            font-size: 10px;
            color: #bbb;
            margin-top: 8px;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="logo-text">EduTech <span class="logo-accent">Library</span></div>
        <div class="logo-sub">Sistem Perpustakaan Digital</div>
        <div class="header-bottom">
            <div class="header-title">
                <h1>Nota<br>Pengembalian</h1>
            </div>
            <div class="header-meta">
                No. {{ $no_nota }}<br>
                Dicetak: {{ $tanggal_cetak }}
            </div>
        </div>
    </div>
    <div class="header-accent"></div>

    {{-- BODY --}}
    <div class="body">

        <div class="status-badge">&#10003; Buku Berhasil Dikembalikan</div>

        {{-- INFORMASI PEMINJAM --}}
        <div class="section-label">Informasi Peminjam</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Nama</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $transaksi->user->name }}</td>
            </tr>
            <tr>
                <td class="info-label">Username</td>
                <td class="info-sep">:</td>
                <td class="info-value">{{ $transaksi->user->username }}</td>
            </tr>
            @if($transaksi->user->nis_nisn)
            <tr>
                <td class="info-label">NIS</td>
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
            <div class="card-box-value">{{ $transaksi->book->judul }}</div>
        </div>

        <div class="two-col">
            <div class="col-left">
                <div class="card-box">
                    <div class="card-box-label">Kode Buku</div>
                    <div class="card-box-value">{{ $transaksi->book->kode_buku }}</div>
                </div>
            </div>
            <div class="col-right">
                <div class="card-box">
                    <div class="card-box-label">Pengarang</div>
                    <div class="card-box-value">{{ $transaksi->book->pengarang }}</div>
                </div>
            </div>
        </div>

        <div class="two-col">
            <div class="col-left">
                <div class="card-box">
                    <div class="card-box-label">Tanggal Pinjam</div>
                    <div class="card-box-value">
                        {{ $transaksi->tanggal_peminjaman->translatedFormat('d F Y') }}
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="card-box">
                    <div class="card-box-label">Jatuh Tempo</div>
                    <div class="card-box-value">
                        {{ $transaksi->tanggal_jatuh_tempo->translatedFormat('d F Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card-box">
            <div class="card-box-label">Tanggal Dikembalikan</div>
            <div class="card-box-value">
            {{ optional($transaksi->tanggal_pengembalian)->translatedFormat('d F Y') ?? '-' }}
            </div>
        </div>

        <hr class="divider">

        {{-- FOOTER --}}
        <div class="footer-row">
            <div class="footer-left">
                Dokumen ini sah sebagai bukti<br>
                pengembalian buku perpustakaan.<br>
                <span style="color: #ccc; font-size: 9px;">{{ $no_nota }}</span>
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
        EduTech Library &bull; Terima kasih telah mengembalikan buku tepat waktu
    </div>

</body>
</html>