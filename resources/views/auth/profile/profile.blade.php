@extends('layouts.app')
@section('title', 'Profile Saya')

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
            <p>{{ auth()->user()->username }}</p>
        </div>
    </div>

    <div class="profile-tabs">
        <button type="button" class="tab-link active" onclick="showTab('profil')">
            <i class="fa fa-user"></i> Profil
        </button>
        <button type="button" class="tab-link" onclick="showTab('password')">
            <i class="fa fa-lock"></i> Reset Password
        </button>
    </div>

    <div class="content-card">
        <!-- PROFIL TAB -->
        <div id="profilTab">
            <h3 class="card-title">Profil {{ auth()->user()->role == 'admin' ? 'Admin' : 'Siswa' }}</h3>
            
            <div class="profile-main-avatar-container">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/'.auth()->user()->profile_photo) }}" alt="Avatar" class="profile-main-avatar">
                @else
                    <div class="profile-main-avatar default-avatar">
                        <i class="fa fa-user"></i>
                    </div>
                @endif
            </div>

            <div class="form-section">
                <div class="field-group">
                    <label>Nama :</label>
                    <div class="display-field">{{ auth()->user()->name }}</div>
                </div>
                
                <div class="field-group">
                    <label>Username :</label>
                    <div class="display-field">{{ auth()->user()->username }}</div>
                </div>
                
                <div class="field-group">
                    <label>No. Telp :</label>
                    <div class="display-field">{{ auth()->user()->telephone ?? '-' }}</div>
                </div>

                @if(auth()->user()->role == 'anggota')
                <div class="field-group">
                    <label>Kelas :</label>
                    <div class="display-field">{{ auth()->user()->kelas ?? '-' }}</div>
                </div>
                <div class="field-group">
                    <label>NISN :</label>
                    <div class="display-field">{{ auth()->user()->nis_nisn ?? '-' }}</div>
                </div>
                @endif

                <div class="btn-group">
                    <a href="{{ route('profile.edit') }}" class="btn-action btn-secondary">
                        <i class="fa fa-pen"></i> Ubah Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- PASSWORD TAB (VIEW MODE) -->
        <div id="passwordTab" class="hidden">
            <h3 class="card-title">Ganti Password</h3>
            
            <div class="form-section">
                <div class="field-group">
                    <label>Password Lama :</label>
                    <div class="display-field">********</div>
                </div>
                
                <div class="field-group">
                    <label>Password Baru :</label>
                    <div class="display-field">********</div>
                </div>

                <div class="btn-group">
                    <a href="{{ route('profile.edit') }}?tab=password" class="btn-action btn-secondary">
                        <i class="fa fa-pen"></i> Ubah Password
                    </a>
                </div>
            </div>
        </div>
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

    // Handle initial tab from query param
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab === 'password') {
            showTab('password');
        }
    });
</script>
@endsection