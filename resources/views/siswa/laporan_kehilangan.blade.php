@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/laporan_kehilangan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-book-open"></i>
            </div>
            <div class="header-text">
                <h5>Laporan Kehilangan Buku</h5>
                <p>Catatan kehilangan buku</p>
            </div>
        </div>
        <img src="{{ asset('img/ikon-buku.png') }}" class="header-image" alt="Ilustrasi Buku">
    </div>

    <!-- FILTER -->
    <form method="GET" action="{{ route('laporan-kehilangan.index') }}" id="filterForm">
        <div class="filter">
            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul / keterangan..."
                       onchange="document.getElementById('filterForm').submit()">
            </div>

            <div class="date">
                <i class="fa fa-calendar"></i>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                       onchange="document.getElementById('filterForm').submit()">
            </div>

            <div class="date" style="min-width: 180px;">
                <i class="fa fa-circle" style="font-size:10px;"></i>
                <select name="status" onchange="document.getElementById('filterForm').submit()"
                        style="border:none; outline:none; background:transparent; font-size:13px; cursor:pointer; width:100%; padding: 0 8px;">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Buku Hilang</option>
                    <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="sudah_dikembalikan" {{ request('status') == 'sudah_dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            @if(request('search') || request('tanggal') || request('status'))
                <a href="{{ route('laporan-kehilangan.index') }}" class="btn-filter" title="Reset" style="text-decoration:none;">
                    <i class="fa fa-times"></i>
                </a>
            @endif
        </div>
    </form>

    <!-- TABLE -->
    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Keterangan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Mengganti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $item)
                    <tr>
                        <td>{{ $reports->firstItem() + $loop->index }}</td>
                        <td>{{ $item->transaction->bookItem->book->judul ?? '-' }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ optional($item->transaction->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ optional($item->tanggal_ganti)->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @if($item->status === 'pending')
                                <span class="status-red">Buku Hilang</span>
                            @elseif($item->status === 'menunggu_konfirmasi')
                                <span class="status-yellow">Menunggu Konfirmasi</span>
                            @elseif($item->status === 'sudah_dikembalikan')
                                <span class="status-green">Sudah Dikembalikan</span>
                            @elseif($item->status === 'rejected')
                                <span class="status-red">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status === 'pending')
                                <form action="{{ route('laporan-kehilangan.kembalikan', $item->id) }}"
                                    method="POST" class="form-kembalikan"
                                    data-judul="{{ $item->transaction->bookItem->book->judul ?? '-' }}"
                                    data-tanggal="{{ optional($item->tanggal_ganti)->format('d/m/Y') ?? '-' }}">
                                    @csrf
                                    <button type="button" class="btn-pengembalian btn-text"
                                            onclick="openModalKembalikan(this)">
                                        Kembalikan Buku
                                    </button>
                                </form>

                            @elseif($item->status === 'rejected')
                                <form action="{{ route('laporan-kehilangan.kembalikan', $item->id) }}"
                                    method="POST" class="form-kembalikan"
                                    data-judul="{{ $item->transaction->bookItem->book->judul ?? '-' }}"
                                    data-tanggal="{{ optional($item->tanggal_ganti)->format('d/m/Y') ?? '-' }}">
                                    @csrf
                                    <button type="button" class="btn-pengembalian btn-text"
                                            onclick="openModalKembalikan(this)">
                                        <i class="fa fa-rotate-left"></i> Ajukan Ulang
                                    </button>
                                </form>

                            @elseif($item->status === 'menunggu_konfirmasi')
                                <span style="font-size: 11px; color: #999; font-style: italic;">
                                    Menunggu admin...
                                </span>

                            @elseif($item->status === 'sudah_dikembalikan')
                                <a href="{{ route('reports.cetak-nota', $item->id) }}"
                                target="_blank" class="btn-pengembalian" title="Cetak Nota"
                                style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:30px; text-decoration:none;">
                                    <i class="fa fa-print"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">Tidak ada laporan kehilangan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div style="margin-top:20px;">
            @include('components.pagination', ['paginator' => $reports])
        </div>
    </div>

<!-- MODAL KONFIRMASI PENGEMBALIAN -->
<div class="modal-overlay" id="modalPengembalian">
    <div class="modal-box">
        <div class="modal-header">
            Ajukan Penggantian Buku
        </div>
        <div class="modal-body">
            <p>Apakah kamu yakin sudah siap mengganti buku <strong id="modalJudulBuku"></strong>?</p>
            <p style="font-size: 13px; color: #666; margin-top: 8px;">
                Tanggal mengganti yang dijanjikan: <strong id="modalTanggalGanti"></strong>
            </p>
            <p style="font-size: 12px; color: #999; margin-top: 4px;">
                Pengajuan akan dikirim ke admin untuk disetujui.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn-batal" id="btnBatal">Batal</button>
            <button class="btn-ya" id="btnYa">Iya, Ajukan Penggantian</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentForm = null;

    function openModalKembalikan(btn) {
        currentForm = btn.closest('form');
        document.getElementById('modalJudulBuku').textContent = currentForm.dataset.judul;
        document.getElementById('modalTanggalGanti').textContent = currentForm.dataset.tanggal;
        document.getElementById('modalPengembalian').style.display = 'flex';
    }

    window.openModalKembalikan = openModalKembalikan;

    document.getElementById('btnBatal').addEventListener('click', function () {
        document.getElementById('modalPengembalian').style.display = 'none';
        currentForm = null;
    });

    document.getElementById('btnYa').addEventListener('click', function () {
        if (currentForm) currentForm.submit();
    });
});
</script>

@endsection