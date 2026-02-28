@extends('layouts.app')

@section('title', 'Laporan Kehilangan Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/laporan_data_kehilangan.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/modal-cetak.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa-solid fa-file-circle-xmark"></i>
        </div>
        <div>
            <h3>Laporan Kehilangan Buku</h3>
            <p>Laporan Buku Yang Hilang</p>
        </div>
    </div>

    <img src="{{ asset('img/ikon-buku.png') }}" class="header-image">
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('reports.index') }}">
    <div class="table-header">
        <div class="filter-group">
            <div class="search-box">
                <i class="fa fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Sesuatu...">
            </div>

            <div class="search-box">
                <i class="fa fa-calendar"></i>
                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()">
            </div>

            <button type="button" class="btn-filter" onclick="this.form.submit()">
                <i class="fa fa-sliders"></i>
            </button>
        </div>

        @auth
        <div class="btn-group-actions">
            <button type="button" class="btn-darkblue" onclick="document.getElementById('modalCetakKehilangan').classList.add('show')">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
        </div>
        @endauth
    </div>
</form>

{{-- TABLE --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Kelas</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Mengganti</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($reports as $index => $report)

        @php
        switch($report->status){
            case 'pending':
                $status = 'Menunggu Konfirmasi';
                $statusClass = 'status-yellow';
                break;

            case 'belum_dikembalikan':
                $status = 'Belum Dikembalikan';
                $statusClass = 'status-red';
                break;

            case 'sudah_dikembalikan':
                $status = 'Sudah Dikembalikan';
                $statusClass = 'status-green';
                break;
            case 'buku_hilang':
                $status = 'Belum Dikembalikan';
                $statusClass = 'status-red';
                break;
            case 'approved':
                $status = 'Disetujui';
                $statusClass = 'status-green';
                break;
            case 'rejected':
                $status = 'Ditolak';
                $statusClass = 'status-red';
                break;

            default:
                $status = ucfirst(str_replace('_', ' ', $report->status));
                $statusClass = 'status-gray';
        }
        @endphp

        <tr>
            <td>{{ $reports->firstItem() + $index }}</td>

            <td>{{ $report->transaction->user->name ?? '-' }}</td>

            <td>{{ $report->transaction->book->judul ?? '-' }}</td>

            <td>{{ $report->transaction->user->kelas ?? '-' }}</td>

            <td>
                {{ optional($report->transaction->tanggal_peminjaman)->format('d/m/Y') }}
            </td>

            <td>
                {{ $report->tanggal_ganti ? \Carbon\Carbon::parse($report->tanggal_ganti)->format('d/m/Y') : '-' }}
            </td>

            <td>
                <span class="{{ $statusClass }}">
                    {{ $status }}
                </span>
            </td>

            <td class="aksi">
                @if($report->status === 'pending')
                    <form action="{{ route('reports.approve', $report->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn ok" title="Setujui">
                            <i class="fa fa-check"></i>
                        </button>
                    </form>

                    <form action="{{ route('reports.reject', $report->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn del" title="Tolak">
                            <i class="fa fa-xmark"></i>
                        </button>
                    </form>
                @elseif($report->status === 'sudah_dikembalikan')
<span class="btn-filter btn-nota"
      onclick="window.open('{{ route('cetak.pengembalian.hilang', $report->id) }}', '_blank')">
    <i class="fa-solid fa-print"></i>
</span>
                @else
                    <span class="no-action">-</span>
                @endif
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="8" style="text-align:center">Data tidak ada</td>
        </tr>
        @endforelse
        </tbody>

    </table>
{{-- PAGINATION --}}
<div style="margin-top:20px;">
    @include('components.pagination', ['paginator' => $reports])
</div>
</div>

@include('components.modal-cetak', [
    'modalId'   => 'modalCetakKehilangan',
    'title'     => 'Filter Data Cetak Kehilangan',
    'filters'   => [
        [
            'id'          => 'status',
            'label'       => 'Status',
            'placeholder' => 'Pilih Status',
            'allOption'   => true,
            'options'     => [
                ['value' => 'belum_dikembalikan', 'label' => 'Belum Diganti'],
                ['value' => 'sudah_dikembalikan', 'label' => 'Sudah Diganti'],
            ],
        ],
    ],
    'routes' => [
        'pdf'   => route('cetak.kehilangan.pdf'),
        'excel' => route('cetak.kehilangan.excel'),
    ],
])

@endsection

{{-- JS --}}
@push('scripts')
<script>
document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('.sidebar')?.classList.toggle('active');
});
</script>
@endpush