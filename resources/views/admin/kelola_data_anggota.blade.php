@extends('layouts.app')

@section('title', 'Kelola Anggota - ' . ucfirst($tab))

@push('styles')
    @if($tab == 'verifikasi')
        <link rel="stylesheet" href="{{ asset('css/admin/kelola-anggota-verifikasi.css') }}">
    @elseif($tab == 'diterima')
        <link rel="stylesheet" href="{{ asset('css/admin/kelola-anggota-diterima.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/admin/kelola-anggota-ditolak.css') }}">
    @endif

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
@endpush

@section('content')

<!-- HEADER -->
<div class="header-card">
    <div class="header-left">
        <div class="header-icon"><i class="fa fa-user-check"></i></div>
        <div>
            <h3>Kelola Anggota</h3>
            <p>
                @if($tab == 'verifikasi')
                    Daftar anggota menunggu verifikasi
                @elseif($tab == 'diterima')
                    Daftar anggota yang telah diterima
                @else
                    Daftar anggota ditolak / non-aktif
                @endif
            </p>
        </div>
    </div>
    <img src="{{ asset('img/book.png') }}" alt="book">
</div>

<!-- TAB -->
<div class="tab-wrapper">
    <a href="{{ route('admin.anggota.index', ['tab'=>'verifikasi']) }}" class="tab-item {{ $tab=='verifikasi'?'active':'' }}">Verifikasi</a>
    <a href="{{ route('admin.anggota.index', ['tab'=>'diterima']) }}" class="tab-item {{ $tab=='diterima'?'active':'' }}">Diterima</a>
    <a href="{{ route('admin.anggota.index', ['tab'=>'ditolak']) }}" class="tab-item {{ $tab=='ditolak'?'active':'' }}">Ditolak</a>
</div>

<div class="table-card">

<!-- FILTER -->
<form method="GET" action="{{ route('admin.anggota.index') }}">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <div class="filter">
        <div class="search">
            <i class="fa fa-search"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari sesuatu...">
        </div>

        @if($tab=='verifikasi')
        <div class="date">
            <i class="fa fa-calendar"></i>
            <input type="date" name="date" value="{{ $date }}">
        </div>
        @endif
    </div>
</form>

<!-- TABLE -->
<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Username</th>
    <th>NIS</th>

    @if($tab=='diterima')
        <th>No. Telp</th>
        <th>Alamat</th>
    @endif

    <th>Kelas</th>

    @if($tab=='verifikasi')
        <th>Tanggal Daftar</th>
    @elseif($tab=='diterima')
        <th>Status</th>
    @endif

    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($users as $user)
<tr>
    <td>{{ $loop->iteration }}</td>

    <td class="user-cell">
        @if($user->profile_photo && file_exists(public_path($user->profile_photo)))
            <img src="{{ asset($user->profile_photo) }}" class="avatar">
        @else
            <div class="avatar avatar-default"><i class="fa fa-user"></i></div>
        @endif
        <div class="user-info">
            <strong>{{ $user->name }}</strong>
            <small>@{{ $user->username }}</small>
        </div>
    </td>

    <td>{{ $user->nis_nisn ?? '-' }}</td>

    @if($tab=='diterima')
        <td>{{ $user->telephone ?? '-' }}</td>
        <td class="alamat-cell">
            {{ $user->alamat && trim($user->alamat) !== '' ? $user->alamat : 'Belum diisi' }}
        </td>
    @endif

    <td>{{ $user->kelas ?? '-' }}</td>

    @if($tab=='verifikasi')
        <td>{{ $user->created_at->format('d/m/Y') }}</td>
    @elseif($tab=='diterima')
        <td><span class="status aktif">DITERIMA</span></td>
    @endif

    <td class="aksi">

        {{-- TAB VERIFIKASI --}}
        @if($tab=='verifikasi')
            <form method="POST" action="{{ route('admin.anggota.status',$user->id) }}" style="display:inline">
                @csrf
                <input type="hidden" name="status" value="aktif">
                <button class="yes"><i class="fa fa-check"></i></button>
            </form>

            <form method="POST" action="{{ route('admin.anggota.status',$user->id) }}" style="display:inline">
                @csrf
                <input type="hidden" name="status" value="ditolak">
                <button class="no"><i class="fa fa-times"></i></button>
            </form>

        {{-- TAB DITERIMA --}}
        @elseif($tab=='diterima')
            <button class="view"><i class="fa fa-eye"></i></button>
            <button class="edit"><i class="fa fa-pen"></i></button>
            <button class="delete"><i class="fa fa-trash"></i></button>

        {{-- TAB DITOLAK --}}
        @elseif($tab=='ditolak')
             <form action="{{ route('admin.anggota.status', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="aktif">
                                            <button type="submit" class="btn-accept"><i class="fa fa-check"></i></button>
                                        </form>
        @endif

    </td>
</tr>

@empty
<tr>
    <td colspan="{{ $tab=='diterima' ? 8 : 7 }}" style="text-align:center;padding:2rem">
        Tidak ada data
    </td>
</tr>
@endforelse
</tbody>

<tfoot>
<tr>
    <td colspan="{{ $tab=='diterima' ? 8 : 7 }}">
        {{ $users->links() }}
    </td>
</tr>
</tfoot>
</table>
</div>
</div>

<script src="{{ asset('js/kelola_anggota.js') }}"></script>
@endsection
