@extends('layouts.app')
@section('title', 'Edit Profile')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="main-content">
    <div class="profile-banner">
        @if(auth()->user()->profile_photo)
            <img src="{{ asset('storage/'.auth()->user()->profile_photo) }}" alt="Avatar" class="banner-avatar">
        @else
            <div class="banner-avatar default-avatar">
                <i class="fa fa-user"></i>
            </div>
        @endif
        <div class="banner-info">
            <h2>{{ auth()->user()->name }}</h2>
            <p>{{ auth()->user()->email ?? auth()->user()->username }}</p>
        </div>
    </div>

    <div class="profile-tabs">
        <a href="{{ route('profile.show') }}?tab=profil" class="tab-link {{ request('tab') != 'password' ? 'active' : '' }}">
            <i class="fa fa-user"></i> Profil
        </a>
        <a href="{{ route('profile.show') }}?tab=password" class="tab-link {{ request('tab') == 'password' ? 'active' : '' }}">
            <i class="fa fa-lock"></i> Reset Password
        </a>
    </div>

    <div class="content-card">
        <!-- EDIT PROFIL TAB -->
        <div id="profilTab" class="{{ request('tab') == 'password' ? 'hidden' : '' }}">
            <h3 class="card-title">Profil {{ auth()->user()->role == 'admin' ? 'Admin' : 'Siswa' }}</h3>
            
            <div class="profile-main-avatar-container">
                <div class="avatar-wrapper" onclick="openPhotoModal()">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/'.auth()->user()->profile_photo) }}" alt="Avatar" class="profile-main-avatar">
                    @else
                        <div class="profile-main-avatar default-avatar">
                            <i class="fa fa-user"></i>
                        </div>
                    @endif
                    <div class="edit-avatar-trigger">
                        <i class="fa fa-camera"></i>
                    </div>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="form-section">
                @csrf
                @method('PUT')
                
                <div class="field-group">
                    <label>Nama :</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="input-field @error('name') is-invalid @enderror" required>
                    @error('name') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                
                <div class="field-group">
                    <label>Username :</label>
                    <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}" class="input-field @error('username') is-invalid @enderror" required>
                    @error('username') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                
                <div class="field-group">
                    <label>No. Telp :</label>
                    <input type="text" name="telephone" value="{{ old('telephone', auth()->user()->telephone) }}" class="input-field @error('telephone') is-invalid @enderror">
                    @error('telephone') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                @if(auth()->user()->role == 'anggota')
                <div class="field-group">
                    <label>Kelas :</label>
                    <input type="text" name="kelas" value="{{ old('kelas', auth()->user()->kelas) }}" class="input-field @error('kelas') is-invalid @enderror">
                </div>
                <div class="field-group">
                    <label>NISN :</label>
                    <input type="text" name="nis_nisn" value="{{ old('nis_nisn', auth()->user()->nis_nisn) }}" class="input-field @error('nis_nisn') is-invalid @enderror">
                </div>
                @endif

                <div class="btn-group">
                    <a href="{{ route('profile.show') }}" class="btn-action btn-danger">Batal</a>
                    <button type="submit" class="btn-action btn-primary">Simpan</button>
                </div>
            </form>
        </div>

        <!-- RESET PASSWORD TAB -->
        <div id="passwordTab" class="{{ request('tab') == 'password' ? '' : 'hidden' }}">
            <h3 class="card-title">Ganti Password</h3>
            
            <form action="{{ route('profile.updatePassword') }}" method="POST" class="form-section">
                @csrf
                @method('PUT')
                
                <div class="field-group">
                    <label>Password Lama :</label>
                    <input type="password" name="old_password" class="input-field @error('old_password') is-invalid @enderror" required>
                    @error('old_password') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                
                <div class="field-group">
                    <label>Password Baru :</label>
                    <input type="password" name="password" class="input-field @error('password') is-invalid @enderror" required>
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                
                <div class="field-group">
                    <label>Konfirmasi Password Baru :</label>
                    <input type="password" name="password_confirmation" class="input-field" required>
                </div>

                <div class="btn-group">
                    <a href="{{ route('profile.show') }}" class="btn-action btn-danger">Batal</a>
                    <button type="submit" class="btn-action btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Photo Update -->
<div id="photoModal" class="hidden" style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:12px; max-width:400px; width:90%;">
        <h4 style="margin-top:0;">Update Foto Profil</h4>
        <form action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="file" name="profile_photo" accept="image/*" class="input-field" style="margin:20px 0;">
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closePhotoModal()" class="btn-action btn-danger" style="padding:8px 20px; font-size:14px; min-width:auto;">Batal</button>
                <button type="submit" class="btn-action btn-primary" style="padding:8px 20px; font-size:14px; min-width:auto;">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showTab(tab) {
        const profilTab = document.getElementById('profilTab');
        const passwordTab = document.getElementById('passwordTab');
        const tabLinks = document.querySelectorAll('.tab-link');

        if (tab === 'profil') {
            profilTab.classList.remove('hidden');
            passwordTab.classList.add('hidden');
            tabLinks[0].classList.add('active');
            tabLinks[1].classList.remove('active');
        } else {
            profilTab.classList.add('hidden');
            passwordTab.classList.remove('hidden');
            tabLinks[1].classList.add('active');
            tabLinks[0].classList.remove('active');
        }
    }

    function openPhotoModal() {
        document.getElementById('photoModal').classList.remove('hidden');
        document.getElementById('photoModal').style.display = 'flex';
    }

    function closePhotoModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.getElementById('photoModal').style.display = 'none';
    }

    // Handle initial state from errors
    @if($errors->has('old_password') || $errors->has('password') || request('tab') == 'password')
        showTab('password');
    @endif
</script>
@endsection