@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/daftar_pengunjung.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/modal-cetak.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/modal-konfirmasi.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <!-- HEADER CARD -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa-solid fa-address-book"></i>
            </div>
            <div>
                <h3>Daftar Pengunjung</h3>
                <p>Mencatat data pengunjung perpustakaan</p>
            </div>
        </div>
        <img src="{{ asset('img/ikon-buku.png') }}" class="header-img">
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">

        <form method="GET" action="{{ route('visits.index') }}">
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
                </div>

                @auth
                <div class="btn-group-actions">
                    <button type="button" class="btn-darkblue" onclick="document.getElementById('modalCetakPengunjung').classList.add('show')">
                        <i class="fa-solid fa-print"></i> Cetak
                    </button>
                </div>
                @endauth
            </div>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengunjung</th>
                        <th>Kelas</th>
                        <th>Tanggal Datang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $visit)
                    <tr>
                        <td>{{ $visits->firstItem() + $loop->index }}</td>
                        <td>{{ $visit->user->name }}</td>
                        <td>{{ $visit->user->kelas ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($visit->tanggal_datang)->format('d/m/Y') }}</td>
                        <td>
                            <button class="btn-delete" data-id="{{ $visit->id }}" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
</tr>
@empty
<tr>
    <td colspan="5" style="text-align:center;">
        Tidak ada data kunjungan
    </td>
</tr>
@endforelse
</tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            @include('components.pagination', ['paginator' => $visits])
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let deleteId = null;
    const modalDelete = document.getElementById('modalDeleteVisit');
    const confirmBtn = document.getElementById('btnConfirm_modalDeleteVisit');

    // Use event delegation for better reliability
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete');
        if (btn) {
            deleteId = btn.dataset.id;
            modalDelete.classList.add('show');
        }
    });

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (!deleteId) return;
            
            confirmBtn.disabled = true;
            const originalHTML = confirmBtn.innerHTML;
            confirmBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            fetch(`{{ route('visits.destroy', ':id') }}`.replace(':id', deleteId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal menghapus data');
                return res.json();
            })
            .then(() => {
                modalDelete.classList.remove('show');
                setTimeout(() => {
                    showToast('Data kunjungan berhasil dihapus', 'success');
                }, 400);
                setTimeout(() => location.reload(), 2000);
            })
            .catch(err => {
                showToast(err.message, 'error');
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalHTML;
                modalDelete.classList.remove('show');
            });
        });
    }
});
</script>
@endpush

    </div>

@include('components.modal-cetak', [
    'modalId' => 'modalCetakPengunjung',
    'title'   => 'Filter Data Cetak Pengunjung',
    'filters' => [
        [
            'id'    => 'hari',
            'label' => 'Hari (Tanggal)',
            'type'  => 'date',
            'placeholder' => '-',
        ],
        [
            'id'    => 'bulan',
            'label' => 'Bulan',
            'placeholder' => '-',
            'allOption' => true,
            'options' => [
                ['value' => '01', 'label' => 'Januari'],
                ['value' => '02', 'label' => 'Februari'],
                ['value' => '03', 'label' => 'Maret'],
                ['value' => '04', 'label' => 'April'],
                ['value' => '05', 'label' => 'Mei'],
                ['value' => '06', 'label' => 'Juni'],
                ['value' => '07', 'label' => 'Juli'],
                ['value' => '08', 'label' => 'Agustus'],
                ['value' => '09', 'label' => 'September'],
                ['value' => '10', 'label' => 'Oktober'],
                ['value' => '11', 'label' => 'November'],
                ['value' => '12', 'label' => 'Desember'],
            ],
        ],
        [
            'id'    => 'tahun',
            'label' => 'Tahun',
            'placeholder' => '-',
            'allOption' => true,
            'options' => $tahunList->map(fn($y) => ['value' => $y, 'label' => $y])->toArray(),
        ],
    ],
    'routes' => [
        'pdf'   => route('cetak.kunjungan.pdf'),
        'excel' => route('cetak.kunjungan.excel'),
    ],
    'formats' => ['excel', 'pdf'],
])

@include('components.modal-konfirmasi', [
    'modalId' => 'modalDeleteVisit',
    'title'   => 'Hapus Data?',
    'message' => 'Apakah Anda yakin ingin menghapus data kunjungan ini?',
    'type'    => 'danger',
    'confirmBtnText' => 'Hapus',
    'cancelBtnText'  => 'Batal'
])

@endsection