@extends('layouts.app')

@section('title', $book ? 'Edit Data Buku' : 'Tambah Data Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/CRUD_kelola_buku.css') }}">
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
                <h3>{{ $book ? 'Edit Data Buku' : 'Kelola Data Buku' }}</h3>
                <p>Mengelola data buku perpustakaan</p>
            </div>
        </div>
        <img src="{{ asset('img/ikon-buku.png') }}" class="header-img">
    </div>

    <div class="card">
        <form 
            action="{{ $book ? route('books.update',$book->id) : route('books.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
        @csrf
        @if($book)
            @method('PUT')
        @endif

        <div class="form-grid">
            <!-- Judul Buku -->
            <div class="form-group span-4">
                <label>Judul Buku</label>
                <input type="text" name="judul" value="{{ old('judul', $book->judul ?? '') }}" placeholder="Masukkan Judul Buku">
                @error('judul')
                    <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Kategori Buku -->
            <div class="form-group span-4">
                <label>Kategori Buku</label>
                <select name="kategori_buku">
                    <option value="">Pilih Kategori Buku</option>
                    <option value="fiksi" {{ old('kategori_buku', $book->kategori_buku ?? '') == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
                    <option value="nonfiksi" {{ old('kategori_buku', $book->kategori_buku ?? '') == 'nonfiksi' ? 'selected' : '' }}>Non Fiksi</option>
                </select>
                @error('kategori_buku')
                    <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Pengarang Buku -->
            <div class="form-group span-4">
                <label>Pengarang Buku</label>
                <input type="text" name="pengarang" value="{{ old('pengarang', $book->pengarang ?? '') }}" placeholder="Masukkan Pengarang Buku">
                @error('pengarang')
                    <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Nomor Rak -->
            <div class="form-group span-3">
                <label>Nomor Rak</label>
                <input type="text" name="nomor_rak" value="{{ old('nomor_rak', $book->row->bookshelf->no_rak ?? '') }}" placeholder="Masukkan Nomor Rak">
                @error('nomor_rak')
                    <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Baris ke -->
            <div class="form-group span-3">
                <label>Baris ke</label>
                <input type="text" name="baris_ke" value="{{ old('baris_ke', $book->row->baris_ke ?? '') }}" placeholder="Masukkan Baris Rak ke-">
                @error('baris_ke')
                    <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Tahun Terbit -->
            <div class="form-group span-3">
                <label>Tahun Terbit</label>
                <input type="text" name="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit ?? '') }}" placeholder="Masukkan Tahun Terbit">
                @error('tahun_terbit')
                    <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

<!-- Kode Buku -->
@if($book)
<div class="form-group span-12" style="margin-top: 8px;">
    <label>Eksemplar Buku</label>
    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
        <thead>
            <tr style="background:var(--color-background-secondary)">
                <th style="padding:8px 12px; text-align:left; font-size:13px;">No</th>
                <th style="padding:8px 12px; text-align:left; font-size:13px;">Kode Buku</th>
                <th style="padding:8px 12px; text-align:left; font-size:13px;">Status</th>
                <th style="padding:8px 12px; text-align:left; font-size:13px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($book->items as $item)
            <tr style="border-bottom:1px solid var(--color-border-tertiary)">
                <td style="padding:8px 12px; font-size:13px;">{{ $loop->iteration }}</td>
                <td style="padding:8px 12px;">
                    <form action="{{ route('books.items.update', $item->id) }}" method="POST" style="display:flex; gap:8px;">
                        @csrf @method('PUT')
                        <input type="text" name="kode_buku" value="{{ $item->kode_buku }}"
                            style="border:1px solid var(--color-border-primary); border-radius:6px; padding:4px 8px; font-size:13px; width:140px;">
                        <button type="submit" style="background:var(--color-background-info); color:var(--color-text-info); border:none; border-radius:6px; padding:4px 10px; cursor:pointer; font-size:12px;">
                            Simpan
                        </button>
                    </form>
                </td>
                <td style="padding:8px 12px; font-size:13px;">
                    <span style="color: {{ $item->status === 'tersedia' ? 'var(--color-text-success)' : 'var(--color-text-danger)' }}">
                        {{ $item->status }}
                    </span>
                </td>
                <td style="padding:8px 12px;">
                    @if($item->status !== 'dipinjam')
                    <form action="{{ route('books.items.destroy', $item->id) }}" method="POST"
                        onsubmit="return confirm('Hapus eksemplar ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:var(--color-background-danger); color:var(--color-text-danger); border:none; border-radius:6px; padding:4px 10px; cursor:pointer; font-size:12px;">
                            Hapus
                        </button>
                    </form>
                    @else
                        <span style="font-size:12px; color:var(--color-text-secondary)">Sedang dipinjam</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding:12px; text-align:center; font-size:13px; color:var(--color-text-secondary)">
                    Belum ada eksemplar
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tambah eksemplar baru --}}
    <form action="{{ route('books.items.store', $book->id) }}" method="POST" style="display:flex; gap:8px; align-items:center;">
        @csrf
        <input type="text" name="kode_buku" placeholder="Kode buku baru (cth: BK-F005)"
            style="border:1px solid var(--color-border-primary); border-radius:6px; padding:6px 12px; font-size:13px; width:220px;">
        <button type="submit" class="btn-simpan" style="margin:0;">
            <i class="fa-solid fa-plus"></i> Tambah Eksemplar
        </button>
    </form>
    @error('kode_buku')
        <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
    @enderror
</div>
@else
<div class="form-group span-3">
    <label>Kode Buku</label>
    <input type="text" name="kode_buku" value="{{ old('kode_buku') }}" placeholder="Masukkan Kode Buku">
    @error('kode_buku')
        <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
    @enderror
</div>
@endif

            @error('id_baris')
                <div class="error-msg span-12" style="margin-top: -10px; margin-bottom: 10px;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- COVER -->
        <div class="form-group span-12" style="margin-top: 20px;">
            <label>Cover Buku <span style="color:red">*</span></label>
            <div class="upload-box" onclick="document.getElementById('coverInput').click()">
                <div id="previewContainer" style="{{ $book && $book->cover ? '' : 'display:none' }}">
                    <img id="coverPreview" src="{{ $book && $book->cover ? asset('storage/'.$book->cover) : '' }}" />
                </div>
                <div id="uploadPlaceholder" style="{{ $book && $book->cover ? 'display:none' : '' }}">
                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                    <p class="upload-text">Klik untuk <br> unggah foto atau dokumen pendukung (PNG, JPG, JPEG, PDF)</p>
                </div>
                <input type="file" name="cover" id="coverInput" accept="image/*" hidden>
            </div>
            @error('cover')
                <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
            @enderror
        </div>

        <!-- SINOPSIS -->
        <div class="form-group span-12" style="margin-top: 30px;">
            <label>Sinopsis Buku</label>
            <textarea name="deskripsi" id="deskripsi" class="editor" placeholder="Masukkan Deskripsi Produk">{{ old('deskripsi', $book->deskripsi ?? '') }}</textarea>
            @error('deskripsi')
                <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-footer">
            <button type="submit" class="btn-simpan">Simpan</button>
        </div>
        </form>
    </div>

<script>
    // Cover preview handler
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('coverInput');
        const preview = document.getElementById('coverPreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        const container = document.getElementById('previewContainer');
        
        if (!input || !preview) return;

        input.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                container.style.display = 'block';
                placeholder.style.display = 'none';
            }
        });
    });
</script>

@endsection