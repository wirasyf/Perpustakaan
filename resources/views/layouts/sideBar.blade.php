<aside class="sidebar">
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}">
        <div class="logo-text-horizontal">
        <span class="edutech">EduTech</span>
        <span class="library">Library</span>
    </div>
    </div>

    <ul class="menu">

        {{-- ================= ADMIN ================= --}}
        @if(Auth::check() && Auth::user()->role === 'admin')

            <li class="sidebar-category">BERANDA</li>
            <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard.admin') }}">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
            </li>

            <li class="sidebar-category">UTAMA</li>
            <li class="{{ Request::is('admin/books*') ? 'active' : '' }}">
                <a href="{{ route('books.index') }}">
                    <i class="fa-solid fa-book-open"></i> Kelola Data Buku
                </a>
            </li>
            <li class="{{ Request::is('admin/anggota*') ? 'active' : '' }}">
                <a href="{{ route('admin.anggota.index', ['tab' => 'verifikasi']) }}">
                    <i class="fa-solid fa-user-plus"></i> Kelola Anggota
                </a>
            </li>
            <li class="{{ Request::is('admin/visits*') ? 'active' : '' }}">
                <a href="{{ route('visits.index') }}">
                    <i class="fa-solid fa-user-group"></i> Daftar Pengunjung
                </a>
            </li>

            <li class="sidebar-category">TRANSAKSI</li>
            <li class="{{ Request::is('admin/transactions*') ? 'active' : '' }}">
                <a href="{{ route('transactions.index') }}">
                    <i class="fa-solid fa-clock-rotate-left"></i> Transaksi
                </a>
            </li>

            <li class="sidebar-category">LAPORAN KEHILANGAN</li>
            <li class="{{ Request::is('admin/reports*') ? 'active' : '' }}">
                <a href="{{ route('reports.index') }}">
                    <i class="fa-solid fa-user-group"></i> Laporan Kehilangan
                </a>
            </li>

        {{-- ================= ANGGOTA ================= --}}
        @elseif(Auth::check() && Auth::user()->role === 'anggota')

            <li class="sidebar-category">BERANDA</li>
            <li class="{{ Request::is('dashboard-siswa*') || Request::is('dashboard-anggota*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.anggota') }}">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
            </li>

            <li class="sidebar-category">UTAMA</li>
            <li class="{{ Request::is('pinjam-buku*') ? 'active' : '' }}">
                <a href="{{ route('books.browse') }}">
                    <i class="fa-solid fa-book-reader"></i> Pinjam Buku
                </a>
            </li>
            <li class="{{ Request::is('my-transactions*') || Request::is('pengembalian-buku*') ? 'active' : '' }}">
                <a href="{{ route('transactions.mine') }}">
                    <i class="fa-solid fa-right-left"></i> Kembalikan Buku
                </a>
            </li>

            <li class="sidebar-category">LAPORAN KEHILANGAN</li>
            <li class="{{ Request::is('laporan-kehilangan*') || Request::is('laporan_kehilangan*') ? 'active' : '' }}">
                <a href="{{ route('laporan-kehilangan.index') }}">
                    <i class="fa-solid fa-file-invoice"></i> Laporan Kehilangan
                </a>
            </li>

        @endif

    </ul>
</aside>

