@extends('layouts.app')
@section('title', 'Pengembalian Buku')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/siswa/pengembalian-buku.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush  
@section('content')

        {{-- HEADER --}}
        <div class="header-card" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 65px; height: 65px; background: rgba(255, 255, 255, 0.2); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 26px; backdrop-filter: blur(4px);">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h5 style="font-size: 22px; font-weight: 700; margin-bottom: 4px; letter-spacing: 0.5px;">Pengembalian Buku</h5>
                    <p style="font-size: 14px; opacity: 0.9; margin: 0;">Pengelolaan pengembalian buku</p>
                </div>
            </div>
            <img src="{{ asset('img/ikon-buku.png') }}" alt="Ilustrasi Buku">
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('anggota.pengembalian') }}" id="filterForm">
            <div style="background:#fff; border-radius:12px; padding:14px 20px; margin-bottom:16px;">
                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">


                    {{-- Search --}}
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari Sesuatu..."
                               onchange="document.getElementById('filterForm').submit()">
                    </div>

                    {{-- Tanggal --}}
                    <div class="search-box">
                        <i class="fa fa-calendar"></i>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                               onchange="document.getElementById('filterForm').submit()"
                               style="min-width: 160px;">
                    </div>

                    {{-- Filter Status --}}
                    <div class="search-box">
                        <i class="fa fa-circle" style="font-size:12px;"></i>
                        <select name="status" onchange="document.getElementById('filterForm').submit()"
                                style="border:none; outline:none; padding: 0 14px; font-size:14px; color:#333; background:transparent; cursor:pointer; min-width: 180px;">
                            <option value="">Semua Status</option>
                            <option value="belum_dikembalikan" {{ request('status') == 'belum_dikembalikan' ? 'selected' : '' }}>Belum Dikembalikan</option>
                            <option value="sudah_dikembalikan" {{ request('status') == 'sudah_dikembalikan' ? 'selected' : '' }}>Selesai</option>
                            <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="buku_hilang" {{ request('status') == 'buku_hilang' ? 'selected' : '' }}>Buku Hilang</option>
                        </select>
                    </div>

                    {{-- Reset / Sliders --}}
                    @if(request('search') || request('tanggal') || request('status'))
                        <a href="{{ route('anggota.pengembalian') }}" class="btn-filter" title="Reset Filter" style="text-decoration:none;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @else
                        <button type="button" class="btn-filter">
                            <i class="fa fa-sliders"></i>
                        </button>
                    @endif

                </div>
            </div>
        </form>


        {{-- TABLE --}}
        <div class="table-card">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kode Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td>{{ $transactions->firstItem() + $loop->index }}</td>
                            <td>{{ $trx->book->judul ?? '-' }}</td>
                            <td>{{ $trx->book->kode_buku ?? '-' }}</td>
                            <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                @if($trx->status == 'belum_dikembalikan')
                                    <span class="status danger">Belum Dikembalikan</span>
                                @elseif($trx->status == 'sudah_dikembalikan')
                                    <span class="status success">✓ Selesai</span>
                                @elseif($trx->status == 'menunggu_konfirmasi')
                                    <span class="status warning">Menunggu Persetujuan</span>
                                @elseif($trx->status == 'terlambat')
                                    <span class="status danger">Terlambat</span>
                                @elseif($trx->status == 'buku_hilang')
                                    <span class="status danger">Buku Hilang</span>
                                @endif
                            </td>
                            <td class="aksi">

                                {{-- BELUM DIKEMBALIKAN --}}
                                @if($trx->status == 'belum_dikembalikan')
                                    <button class="aksi-btn blue" data-bs-toggle="modal"
                                        data-bs-target="#modalKembalikan{{ $trx->id }}" title="Kembalikan Buku">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </button>
                                    <button class="aksi-btn orange" data-bs-toggle="modal"
                                        data-bs-target="#modalPerpanjang{{ $trx->id }}" title="Perpanjang">
                                        <i class="bi bi-calendar-event"></i>
                                    </button>
                                    <button class="aksi-btn red" data-bs-toggle="modal"
                                        data-bs-target="#modalKehilangan{{ $trx->id }}" title="Laporan Kehilangan">
                                        <i class="bi bi-chat-dots"></i>
                                    </button>
                                    {{-- Cetak Nota Peminjaman (biru) --}}
                                    <a href="{{ route('transactions.cetak-nota', [$trx->id, 'peminjaman']) }}"
                                       target="_blank" class="aksi-btn" title="Cetak Nota Peminjaman"
                                       style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white;
                                              text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                @endif

                                {{-- TERLAMBAT --}}
                                @if($trx->status == 'terlambat')
                                    <button class="aksi-btn blue" data-bs-toggle="modal"
                                        data-bs-target="#modalKembalikan{{ $trx->id }}" title="Kembalikan Buku">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </button>
                                    <button class="aksi-btn orange" data-bs-toggle="modal"
                                        data-bs-target="#modalPerpanjang{{ $trx->id }}" title="Perpanjang">
                                        <i class="bi bi-calendar-event"></i>
                                    </button>
                                    <a href="{{ route('transactions.cetak-nota', [$trx->id, 'peminjaman']) }}"
                                       target="_blank" class="aksi-btn" title="Cetak Nota Peminjaman"
                                       style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white;
                                              text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                @endif

                                {{-- MENUNGGU KONFIRMASI --}}
                                @if($trx->status == 'menunggu_konfirmasi')
                                    <span style="font-size: 11px; color: #999; font-style: italic;">Menunggu admin...</span>
                                @endif

                                {{-- SELESAI: Cetak Nota Pengembalian (oranye) --}}
                                @if($trx->status == 'sudah_dikembalikan')
                                    <a href="{{ route('transactions.cetak-nota', [$trx->id, 'pengembalian']) }}"
                                       target="_blank" class="aksi-btn" title="Cetak Nota Pengembalian"
                                       style="background: linear-gradient(135deg, #f5a623, #e8832a); color: white;
                                              text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                @endif

                                {{-- BUKU HILANG --}}
                                @if($trx->status == 'buku_hilang')
                                    <span style="font-size: 11px; color: #999; font-style: italic;">Lihat laporan kehilangan</span>
                                @endif

                            </td>
                        </tr>

                        {{-- Modal Kembalikan --}}
                        @if(in_array($trx->status, ['belum_dikembalikan', 'terlambat']))
                        <div class="modal fade" id="modalKembalikan{{ $trx->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header custom-header">
                                        <h5 class="modal-title">Kembalikan Buku</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p>Apakah kamu yakin ingin mengembalikan <strong>{{ $trx->book->judul }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('transactions.ajukanPengembalian', $trx->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-yakin btn-rounded">Iya, Kembalikan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Modal Perpanjang --}}
                        @if(in_array($trx->status, ['belum_dikembalikan', 'terlambat']))
                        <div class="modal fade" id="modalPerpanjang{{ $trx->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header custom-header">
                                        <h5 class="modal-title">Perpanjang Peminjaman</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p class="fs-6">Apakah kamu yakin ingin memperpanjang <strong>{{ $trx->book->judul }}</strong> selama <strong>3 hari</strong>?</p>
                                        <small class="text-muted">Jatuh tempo saat ini: {{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('transactions.perpanjang', $trx->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-yakin btn-rounded">Iya, Perpanjang</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Modal Laporan Kehilangan --}}
                        @if($trx->status == 'belum_dikembalikan')
                        <div class="modal fade" id="modalKehilangan{{ $trx->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header custom-header">
                                        <h5 class="modal-title">Laporan Kehilangan Buku</h5>
                                    </div>
                                    <form action="{{ route('laporan-kehilangan.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="transactions_id" value="{{ $trx->id }}">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Judul Buku</label>
                                                <input type="text" class="form-control" value="{{ $trx->book->judul }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">
                                                    Tanggal Mengganti Buku
                                                    <small class="text-muted fw-normal">(minimal 5 hari dari sekarang)</small>
                                                </label>
                                                <input type="date" class="form-control" name="tanggal_ganti"
                                                    min="{{ now()->addDays(5)->format('Y-m-d') }}" required>
                                                <small class="text-muted">Paling cepat: <strong>{{ now()->addDays(5)->translatedFormat('d F Y') }}</strong></small>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Alasan Kehilangan</label>
                                                <textarea class="form-control" name="keterangan" rows="5"
                                                    placeholder="Jelaskan alasan buku Anda hilang..." required maxlength="500"></textarea>
                                                <small class="text-muted d-block text-end mt-1">Max 500 karakter</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-simpan btn-rounded">Lapor Kehilangan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
<<<<<<< HEAD

=======
            </div>
>>>>>>> 84a9b608856ab47e5d6b68e302cf7881547cc46b
            {{-- PAGINATION --}}
            <div style="margin-top:20px;">
                @include('components.pagination', ['paginator' => $transactions])
            </div>
        </div>

    </main>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection