
@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/dashboard-anggota.css') }}">
    <link rel="stylesheet" href="{{ asset('css/siswa/card.css') }}">
@endpush

@section('content')
    <h1>Selamat Datang di Dashboard Anggota</h1>
@endsection
