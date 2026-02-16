@extends('layouts.app')
@section('title', 'Profil Siswa')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/profile-siswa.css') }}">
@endpush
@section('content')

        <!-- HEADER -->
        <div class="header">
            <h2>Profile saya</h2>
        </div>

        <!-- PROFILE CARD -->
        <div class="profile-wrapper">

            <div class="card profile-card">
                <div class="avatar">
                    <img src="{{ asset('img/avatar.png') }}">
                    <span class="edit-icon"><i class="fa fa-pen"></i></span>
                </div>
                <h3>AURELLYA AMANDA P.A</h3>
                <span class="role">SISWA</span>
            </div>

            <div class="card biodata-card">
                <h4>Data diri</h4>
                <div class="biodata">
                    <p><strong>Nama</strong> : aurellya.amanda.p.a</p>
                    <p><strong>Jenis Kelamin</strong> : Perempuan</p>
                    <p><strong>Kelas</strong> : XII - RPL 1</p>
                    <p><strong>Username</strong> : aurellya.m</p>
                    <p><strong>NISN</strong> : 12345678</p>
                    <p><strong>No.Telepon</strong> : 081914547345</p>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card table-card">
            <h4>Riwayat kunjungan</h4>
            <table>
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>nama_buku</th>
                        <th>Tanggal Datang</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Aurellya</td>
                        <td>Peminjaman</td>
                        <td>24/01/2026</td>
                    </tr>
                    <tr>
                        <td>Aurellya</td>
                        <td>Pengembalian</td>
                        <td>23/01/2026</td>
                    </tr>
                    <tr>
                        <td>Aurellya</td>
                        <td>Peminjaman</td>
                        <td>22/01/2026</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button class="btn-edit"><i class="fa fa-pen"></i> edit profil</button>

    </main>
</div>

<!-- JS (DIGABUNG) -->
<script>
    const userBtn = document.getElementById('userBtn');
    const dropdown = document.getElementById('dropdown');

    userBtn.addEventListener('click', () => {
        dropdown.classList.toggle('show');
    });
</script>

@endsection
