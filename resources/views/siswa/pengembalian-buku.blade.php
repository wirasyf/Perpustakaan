@extends('layouts.app')
@section('title', 'Pengembalian Buku')
@push('styles')
    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CSS --}}
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
        <div class="filter-card">
            <div class="filter-item">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari sesuatu...">
            </div>

            <div class="filter-item">
                <i class="bi bi-calendar"></i>
                <input type="date">
            </div>

            <button class="btn-filter">
                <i class="bi bi-sliders"></i>
            </button>
        </div>

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
                                {{-- Kembalikan Buku (hanya jika belum dikembalikan) --}}
                                @if($trx->status == 'belum_dikembalikan')
                                    <button class="aksi-btn blue"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKembalikan{{ $trx->id }}"
                                        title="Kembalikan Buku">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </button>
                                @endif

                                {{-- Perpanjang (hanya jika belum dikembalikan/terlambat) --}}
                                @if(in_array($trx->status, ['belum_dikembalikan', 'terlambat']))
                                    <button class="aksi-btn orange"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPerpanjang{{ $trx->id }}"
                                        title="Perpanjang">
                                        <i class="bi bi-calendar-event"></i>
                                    </button>
                                @endif

                                {{-- Laporan Kehilangan (hanya jika belum dikembalikan) --}}
                                @if($trx->status == 'belum_dikembalikan')
                                    <button class="aksi-btn red"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalKehilangan{{ $trx->id }}"
                                        title="Laporan Kehilangan">
                                        <i class="bi bi-chat-dots"></i>
                                    </button>
                                @endif
                                {{-- CETAK NOTA — otomatis muncul saat status = sudah_dikembalikan --}}
                                @if($trx->status == 'sudah_dikembalikan')
                                    <a href="{{ route('transactions.cetak-nota', $trx->id) }}"
                                    target="_blank"
                                    class="aksi-btn"
                                    title="Cetak Nota Pengembalian"
                                    style="background: linear-gradient(135deg, #f5a623, #e8832a);
                                            color: white; text-decoration: none;
                                            display: inline-flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal Kembalikan Buku --}}
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
                                        <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <form action="{{ route('transactions.ajukanPengembalian', $trx->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-yakin btn-rounded">
                                                Iya, Kembalikan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Perpanjang --}}
                        <div class="modal fade" id="modalPerpanjang{{ $trx->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header custom-header">
                                        <h5 class="modal-title">Perpanjang Peminjaman</h5>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p class="fs-6">
                                            Apakah kamu yakin ingin memperpanjang waktu peminjaman <strong>{{ $trx->book->judul }}</strong> selama <strong>3 hari</strong>?
                                        </p>
                                        <small class="text-muted">
                                            Jatuh tempo saat ini: {{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <form action="{{ route('transactions.perpanjang', $trx->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-yakin btn-rounded">
                                                Iya, Perpanjang
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modal Laporan Kehilangan --}}
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
                                                <textarea class="form-control" name="keterangan" rows="5" placeholder="Jelaskan alasan buku Anda hilang..." required maxlength="500"></textarea>
                                                <small class="text-muted d-block text-end mt-1">Max 500 karakter</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-simpan btn-rounded">
                                                Lapor Kehilangan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            {{-- PAGINATION --}}
<div style="margin-top:20px;">
    @include('components.pagination', ['paginator' => $transactions])
</div>
</div>
        </div>

    </main>
</div>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
