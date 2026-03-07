@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/laporan_kehilangan.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

            <!-- HEADER -->
            <div class="header-card" style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 65px; height: 65px; background: rgba(255, 255, 255, 0.2); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 26px; backdrop-filter: blur(4px);">
                        <i class="fa fa-book-open"></i>
                    </div>
                    <div>
                        <h5 style="font-size: 22px; font-weight: 700; margin-bottom: 4px; letter-spacing: 0.5px;">Laporan Kehilangan Buku</h5>
                        <p style="font-size: 14px; opacity: 0.9; margin: 0;">Catatan kehilangan buku</p>
                    </div>
                </div>
                <img src="{{ asset('img/ikon-buku.png') }}" class="header-image" alt="Ilustrasi Buku" style="width: 65px;">
            </div>

            <!-- FILTER -->
            <div class="filter">
                <div class="search">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Cari sesuatu...">
                </div>

                <div class="date">
                    <i class="fa fa-calendar"></i>
                    <input type="date">
                </div>

                <button class="btn-filter">
                    <i class="fa fa-sliders"></i>
                </button>
            </div>

            <!-- NOTIFIKASI -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-bottom: 16px;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-bottom: 16px;">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- TABLE -->
            <div class="table-card">
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
                            <td>{{ $item->transaction->book->judul ?? '-' }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>{{ optional($item->transaction->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ optional($item->tanggal_ganti)->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                @if($item->status === 'pending')
                                    <span class="status-yellow">Menunggu Konfirmasi</span>
                                @elseif($item->status === 'sudah_dikembalikan')
                                    <span class="status-green">Sudah Dikembalikan</span>
                                @elseif($item->status === 'belum_dikembalikan')
                                    <span class="status-red">Belum Dikembalikan</span>
                                @else
                                    <span class="status-gray">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                @endif
                            </td>
                            <td>
                                {{-- Kembalikan: hanya jika belum_dikembalikan --}}
                                @if($item->status === 'belum_dikembalikan')
                                    <form action="{{ route('laporan-kehilangan.kembalikan', $item->id) }}"
                                          method="POST" style="display: inline;" class="form-kembalikan"
                                          data-judul="{{ $item->transaction->book->judul ?? '-' }}"
                                          data-tanggal="{{ optional($item->tanggal_ganti)->format('d/m/Y') ?? '-' }}">
                                        @csrf
                                        <button type="submit" class="btn-pengembalian" title="Ajukan Penggantian Buku">
                                            <i class="fa fa-rotate-left"></i>
                                        </button>
                                    </form>
                                @endif

                                {{-- Menunggu konfirmasi: tidak ada aksi --}}
                                @if($item->status === 'pending')
                                    <span class="no-action" style="font-size: 11px; color: #999; font-style: italic;">
                                        Menunggu admin...
                                    </span>
                                @endif

                                {{-- Cetak Nota — muncul jika sudah sudah_dikembalikan --}}
                                @if($item->status === 'sudah_dikembalikan')
                                    <a href="{{ route('reports.cetak-nota', $item->id) }}"
                                       target="_blank"
                                       class="btn-pengembalian"
                                       title="Cetak Nota Penggantian"
                                       style="background: linear-gradient(135deg, #f5a623, #e8832a);
                                              color: white; text-decoration: none;
                                              display: inline-flex; align-items: center; justify-content: center;">
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
                
                {{-- PAGINATION --}}
                <div style="margin-top:20px;">
                    @include('components.pagination', ['paginator' => $reports])
                </div>
            </div>

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

    document.querySelectorAll('.form-kembalikan .btn-pengembalian').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            currentForm = this.closest('form');

            // Tampilkan info buku di modal
            const judul   = currentForm.dataset.judul;
            const tanggal = currentForm.dataset.tanggal;
            document.getElementById('modalJudulBuku').textContent    = judul;
            document.getElementById('modalTanggalGanti').textContent = tanggal;

            document.getElementById('modalPengembalian').style.display = 'flex';
        });
    });

    document.getElementById('btnBatal').addEventListener('click', function () {
        document.getElementById('modalPengembalian').style.display = 'none';
        currentForm = null;
    });

    document.getElementById('btnYa').addEventListener('click', function () {
        if (currentForm) {
            currentForm.submit();
        }
    });

});
</script>

@endsection