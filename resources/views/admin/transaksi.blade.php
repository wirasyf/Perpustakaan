@extends('layouts.app')

@section('title', 'Transaksi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/transaksi.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/card.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/modal-cetak.css') }}">
@endpush
@section('content')

<!-- HEADER -->
<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa-solid fa-book-bookmark"></i>
        </div>
        <div>
            <h3>Transaksi</h3>
            <p>Peminjaman dan pengembalian buku</p>
        </div>
    </div>
    <img src="{{ asset('img/ikon-buku.png') }}" class="header-img">
</div>

<!-- TAB -->
<div class="tab-wrapper">
    <a href="?mode=peminjaman"
       class="tab-item {{ ($mode ?? 'peminjaman') == 'peminjaman' ? 'active' : '' }}">
        Peminjaman
    </a>

    <a href="?mode=pengembalian"
       class="tab-item {{ ($mode ?? '') == 'pengembalian' ? 'active' : '' }}">
        Pengembalian
    </a>
</div>

<!-- FILTER -->
<form method="GET" action="{{ route('transactions.index') }}">
    <input type="hidden" name="mode" value="{{ $mode ?? 'peminjaman' }}">
    <div class="table-header">
        <div class="filter-group">
            <div class="search-box">
                <i class="fa fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Sesuatu...">
            </div>

            <div class="search-box">
                <i class="fa fa-calendar"></i>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" onchange="this.form.submit()">
            </div>

                                    <div class="search-box">
                            <i class="fa fa-graduation-cap"></i>
                            <select name="kelas" onchange="this.form.submit()" style="border:none; outline:none; background:transparent;">
                                <option value=""> Semua Kelas </option>
                                @foreach($kelasList as $k)
                                    <option value="{{ $k }}" {{ $kelas == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


            @if($mode == 'peminjaman')
            <div class="search-box">
                <i class="fa fa-circle"></i>
                <select name="status" onchange="this.form.submit()" style="border:none; outline:none; background:transparent;">
                    <option value="">Semua Status</option>
                    <option value="belum_dikembalikan"    {{ ($status ?? '') == 'belum_dikembalikan'    ? 'selected' : '' }}>Belum Dikembalikan</option>
                    <option value="sudah_dikembalikan"    {{ ($status ?? '') == 'sudah_dikembalikan'    ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="terlambat"             {{ ($status ?? '') == 'terlambat'             ? 'selected' : '' }}>Terlambat</option>
                    <option value="buku_hilang"           {{ ($status ?? '') == 'buku_hilang'           ? 'selected' : '' }}>Buku Hilang</option>
                    <option value="menunggu_konfirmasi"   {{ ($status ?? '') == 'menunggu_konfirmasi'   ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                </select>
            </div>
            @endif

        </div>

        @auth
        @if($mode == 'peminjaman')
        <div class="btn-group-actions">
            <button type="button" class="btn-darkblue" onclick="document.getElementById('modalCetakLaporan').classList.add('show')">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
        </div>
        @endif
        @endauth
    </div>
</form>

{{-- ================= PEMINJAMAN ================= --}}
@if(($mode ?? 'peminjaman') == 'peminjaman')

<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Kelas</th>
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
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
    @if($trx->status == 'belum_dikembalikan')
        <span class="status danger">Sedang dipinjam</span>
    @elseif($trx->status == 'buku_hilang')
        <span class="status warning">Buku Hilang</span>
    @elseif($trx->status == 'sudah_dikembalikan')
        <span class="status success">Sudah Dikembalikan</span>
    @elseif($trx->status == 'terlambat')
        <span class="status danger">Terlambat</span>
    @elseif($trx->status == 'menunggu_konfirmasi')
        <span class="status warning">Menunggu Konfirmasi</span>
    @endif
    </td>
<td class="aksi">
@if($trx->status == 'belum_dikembalikan')
<span class="btn-filter btn-nota"
      onclick="window.open('{{ route('cetak.nota', [$trx->id, 'peminjaman']) }}', '_blank')">
    <i class="fa-solid fa-print"></i>
</span>
@elseif(in_array($trx->status, ['terlambat', 'buku_hilang']))
    <span>-</span>
@endif
</td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            @include('components.pagination', ['paginator' => $transactions])
                        </td>
                    </tr>
                </tfoot>
</table>
</div>
@endif

{{-- ================= PENGEMBALIAN ================= --}}
@if(($mode ?? '') == 'pengembalian')

<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Kelas</th>
    <th>Jatuh Tempo</th>
    <th>Status</th>
    <th>Tgl Kembali</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $transactions->firstItem() + $loop->index }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
        @if($trx->status == 'menunggu_konfirmasi')
            <span class="status warning">Menunggu Persetujuan</span>
        @elseif($trx->status == 'sudah_dikembalikan')
            <span class="status success">Sudah Dikembalikan</span>
        @endif
    </td>
    <td>{{ $trx->tanggal_pengembalian ? $trx->tanggal_pengembalian->format('d/m/Y') : '-' }}</td>
    <td class="aksi" style="display: flex; gap: 5px; justify-content: center;">
@if($trx->status == 'menunggu_konfirmasi')
    <form action="{{ route('transactions.terimaPengembalian', $trx->id) }}" method="POST" onsubmit="return confirm('Terima pengembalian buku ini?')">
        @csrf
        @method('PUT')
        <button type="submit" class="btn-green" title="Terima" style="border:none; border-radius:4px; padding: 2px 8px; cursor:pointer;">✔</button>
    </form>
    <form action="{{ route('transactions.tolakPengembalian', $trx->id) }}" method="POST" onsubmit="return confirm('Tolak pengembalian buku ini?')">
        @csrf
        @method('PUT')
        <button type="submit" class="btn-red" title="Tolak" style="border:none; border-radius:4px; padding: 2px 8px; cursor:pointer;">✖</button>
    </form>
@elseif($trx->status == 'sudah_dikembalikan')
<span class="btn-filter btn-nota"
      onclick="window.open('{{ route('cetak.nota', [$trx->id, 'pengembalian']) }}', '_blank')">
    <i class="fa-solid fa-print"></i>
</span>
@endif
</td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            @include('components.pagination', ['paginator' => $transactions])
                        </td>
                    </tr>
                </tfoot>
</table>
</div>
@endif
@include('components.modal-cetak', [
    'modalId'   => 'modalCetakLaporan',
    'title'     => 'Filter Data Cetak Laporan',
    'filters'   => [
        [
            'id'          => 'status',
            'label'       => 'Status',
            'placeholder' => 'Pilih Status',
            'allOption'   => true,
            'options'     => [
                ['value' => 'belum_dikembalikan', 'label' => 'Belum Dikembalikan'],
                ['value' => 'sudah_dikembalikan', 'label' => 'Sudah Dikembalikan'],
            ],
        ],
    ],
    'routes' => [
        'pdf'   => route('cetak.transaksi.pdf'),
        'excel' => route('cetak.transaksi.excel'),
    ],
])
@endsection
