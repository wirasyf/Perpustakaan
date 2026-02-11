{{-- resources/views/buku/create.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Buku</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/CRUD_kelola_buku.css') }}">
</head>
<body>
<div class="app">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
        </div>

        <ul class="menu">
            <li class="{{ request()->is('kelola_data_buku*') ? '' : '' }}">
                <a href="/kelola_data_buku">
                    <i class="fa fa-book"></i> Kelola Data Buku
                </a>
            </li>

            <li class="{{ request()->is('kelola_anggota*') ? '' : '' }}">
                <a href="/kelola_anggota">
                    <i class="fa fa-users"></i> Kelola Anggota
                </a>
            </li>

            <li class="{{ request()->is('transaksi*') ? '' : '' }}">
                <a href="/transaksi">
                    <i class="fa fa-right-left"></i> Transaksi
                </a>
            </li>

        <li class="{{ request()->is('daftar_pengunjung') ? '' : '' }}">
        <a href="/daftar_pengunjung">
            <i class="fa fa-list"></i> Daftar Pengunjung
        </a>
    </li>


            <li class="{{ request()->is('laporan_kehilangan*') ? '' : '' }}">
                <a href="/laporan_kehilangan">
                    <i class="fa fa-file"></i> Laporan Kehilangan
                </a>
            </li>
        </ul>
    </aside>

    <!-- Content -->
    <main class="content">
        <!-- TOPBAR -->
        <div class="topbar">
            <i class="fa-solid fa-bars"></i>
            <div class="user">
                <span>Seulgi</span>
                <img src="{{ asset('img/user.png') }}" alt="User">
            </div>
        </div>

        <div class="header-card">
            <div>
                <h2>Kelola Data Buku</h2>
                <p>Mengelola data buku perpustakaan</p>
            </div>
            📚
        </div>

        <div class="card">
            <form>
               <div class="form-grid">
                         
    <!-- Judul Buku -->
    <div class="form-group col-1">
        <label>Judul Buku</label>
        <input type="text" placeholder="Masukkan Judul Buku">
        <small class="error">Judul wajib diisi</small>
    </div>

    <!-- Kategori Buku -->
    <div class="form-group col-2">
        <label>Kategori Buku</label>
        <input type="text" placeholder="Pilih Kategori Buku">
        <small class="error">Kategori wajib diisi</small>
    </div>

    <!-- Pengarang Buku -->
   <div class="form-group col-1">
        <label>Pengarang Buku</label>
        <input type="text" placeholder="Masukkan Pengarang Buku">
        <small class="error">Pengarang wajib diisi</small>
    </div>

    <!-- Nomor Rak -->
  <div class="form-group col-1">
        <label>Nomor Rak</label>
        <input type="text" placeholder="Masukkan Nomor Rak">
        <small class="error">Nomor rak wajib diisi</small>
    </div>

    <!-- Baris ke -->
    <div class="form-group col-1">
        <label>Baris ke</label>
        <input type="text" placeholder="Masukkan Baris Rak ke-">
        <small class="error">Baris wajib diisi</small>
    </div>

    <!-- Tahun Terbit -->
    <div class="form-group col-1">
        <label>Tahun Terbit</label>
        <input type="number" placeholder="Masukkan Tahun Terbit">
        <small class="error">Tahun terbit wajib diisi</small>
    </div>

    <!-- Kode Buku -->
   <div class="form-group col-1">
        <label>Kode Buku</label>
        <input type="text" placeholder="Masukkan Kode Buku">
        <small class="error">Kode buku wajib diisi</small>
    </div>

</div>


                <div class="form-group" style="margin-top:20px">
                    <label>Cover Buku</label>
                    <div class="upload-box">
                        ⬆️ Klik untuk upload<br>
                        (PNG, JPG, JPEG,)
                    </div>
                    <span class="error">Cover wajib diisi</span>
                </div>

                <div class="form-group" style="margin-top:20px">
                    <label>Sinopsis Buku</label>
                    <div class="editor" contenteditable="true">Masukkan deskripsi produk</div>
                    <span class="error">Deskripsi wajib diisi</span>
                </div>

              <button class="btn">Simpan</button>
            </form>

        </div>
    </main>
</div>
</body>
</html>
