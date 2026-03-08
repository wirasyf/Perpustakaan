
@extends('layouts.app')

@section('title', 'Kelola Data Buku')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/kelola_data_buku.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/modal-cetak.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
        <!-- HEADER CARD -->
        <div class="header-card">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fa-solid fa-book-medical"></i>
                </div>
                <div>
                    <h3>Kelola Data Buku</h3>
                    <p>Mengelola data buku perpustakaan</p>
                </div>
            </div>
            <img src="{{ asset('img/ikon-buku.png') }}" alt="Ilustrasi Buku">
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">

            <div class="table-header">
                <form method="GET" action="{{ route('books.index') }}">
                    <div class="filter-group">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Sesuatu...">
                        </div>
                        
                        <!-- FILTER TAHUN -->
                        <div class="search-box">
                            <i class="fa-solid fa-calendar-days"></i>
                            <select name="date" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('date') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- DROPDOWN KATEGORI -->
                        <div class="search-box">
                            <i class="fa-solid fa-layer-group"></i>
                            <select name="filter" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <option value="fiksi" {{ request('filter') == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
                                <option value="nonfiksi" {{ request('filter') == 'nonfiksi' ? 'selected' : '' }}>Non Fiksi</option>
                            </select>
                        </div>
                    </div>
                </form>

                @auth
                <div class="btn-group-actions">
                    <button type="button" class="btn-darkblue" onclick="document.getElementById('modalCetakBuku').classList.add('show')">
                        <i class="fa-solid fa-print"></i> Cetak
                    </button>
                    <a href="{{ route('books.create') }}" class="btn-darkblue">
                        <i class="fa-solid fa-plus"></i> Tambah Data Buku
                    </a>
                </div>
                @endauth
            </div>
            
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Kode Buku</th>
                            <th>Pengarang</th>
                            <th>Tahun Terbit</th>
                            <th>Kategori</th>
                            <th>Rak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($books as $book)
                        @if (Auth::user()->role == 'admin')
                        <tr>
                            <td>{{ $books->firstItem() + $loop->index }}</td>
                            <td>{{ $book->judul }}</td>
                            <td>{{ $book->kode_buku }}</td>
                            <td>{{ $book->pengarang }}</td>
                            <td>{{ $book->tahun_terbit }}</td>
                            <td>{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}</td>
                            <td>
                                {{ $book->row?->bookshelf?->no_rak }} - {{ $book->row?->baris_ke ?? $book->id_baris }}
                            </td>
                            <td class="aksi">
                                @auth
                                <a href="{{ route('books.edit', $book->id) }}" class="btn edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
          <button class="btn delete" onclick="openModal(this)" data-id="{{ $book->id }}">
        <i class="fa-solid fa-trash"></i>
    </button>
                                @endauth

    <button class="btn view"
        onclick="openDetail(this)"
        data-judul="{{ $book->judul }}"
        data-penulis="{{ $book->pengarang }}"
        data-kategori="{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}"
        data-deskripsi="{{ $book->deskripsi }}"
        data-gambar="{{ $book->cover_url }}"
    >
        <i class="fa-solid fa-eye"></i>
    </button>

                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="8">
                                @include('components.pagination', ['paginator' => $books])
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
    </div>

    <!-- ================= MODAL HAPUS ================= -->
    <div class="modal-overlay" id="modalHapus" style="display:none;">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Hapus Data Buku</h3>
            </div>

            <div class="modal-body">
                <p>Apakah kamu yakin ingin menghapus data buku?</p>
            </div>

            <div class="modal-footer">
                <button class="btn-modal batal" onclick="closeModal()">Batal</button>
                <button class="btn-modal yakin" onclick="hapusData()">Iya, saya yakin</button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL DETAIL BUKU ================= -->
    <div class="modal-overlay" id="modalDetail" style="display:none;">
        <div class="modal-detail-box">
            <div class="modal-header">
                <h3>Detail Buku</h3>
            </div>

            <div class="modal-detail-body">
                <img id="detailGambar" src="" alt="Buku">

                <div class="detail-text">
                    <h2 id="detailJudul"></h2>
                    <p class="penulis">By: <span id="detailPenulis"></span></p>
                    <span class="badge" id="detailKategori"></span>
                    <p class="deskripsi" id="detailDeskripsi"></p>
                </div>
            </div>

            <div class="modal-footer-detail">
                <button class="btn-tutup" onclick="closeDetail()">Tutup</button>
            </div>
        </div>
    </div>

@include('components.modal-cetak', [
    'modalId'   => 'modalCetakBuku',
    'title'     => 'Filter Data Export Buku',
    'filters'   => [
        [
            'id'          => 'kategori',
            'label'       => 'Kategori',
            'placeholder' => 'Pilih Kategori',
            'allOption'   => true,
            'options'     => [
                ['value' => 'fiksi', 'label' => 'Fiksi'],
                ['value' => 'nonfiksi', 'label' => 'Non Fiksi'],
            ],
        ],
    ],
    'routes' => [
        'excel' => route('books.exportExcel'),
    ],
    'formats' => ['excel'],
])
@endsection

@push('scripts')
<script>
    let selectedRow = null;
    let selectedId = null;

    function openModal(button) {
        selectedRow = button.closest('tr');
        selectedId = button.getAttribute('data-id');
        document.getElementById('modalHapus').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalHapus').style.display = 'none';
    }

    function hapusData() {
        fetch(`/books/${selectedId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                selectedRow.remove();
                closeModal();
                alert('Buku berhasil dihapus');
            } else {
                alert('Error: ' + (data.message || 'Gagal menghapus data'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Gagal menghapus data: ' + err.message);
        });
    }

    document.getElementById('modalHapus').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function openDetail(btn) {
        document.getElementById('detailJudul').innerText = btn.dataset.judul;
        document.getElementById('detailPenulis').innerText = btn.dataset.penulis;
        document.getElementById('detailKategori').innerText = btn.dataset.kategori;
        document.getElementById('detailDeskripsi').innerText = btn.dataset.deskripsi;
        document.getElementById('detailGambar').src = btn.dataset.gambar;
        document.getElementById('modalDetail').style.display = 'flex';
    }

    function closeDetail() {
        document.getElementById('modalDetail').style.display = 'none';
    }

    document.getElementById('modalDetail').addEventListener('click', function(e) {
        if (e.target === this) closeDetail();
    });
</script>
@endpush
