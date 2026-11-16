
@extends('layouts.pjlab')

@section('title', 'Dashboard')

@section('content')

    <div class="dashboard-heading">

        <div>

            <h4 class="dashboard-title">
                Dashboard
            </h4>

            <p class="dashboard-subtitle">
                Ringkasan kondisi dan aktivitas
                laboratorium komputer hari ini.
            </p>

        </div>

        <div class="text-muted small">

            <i class="bi bi-calendar3 me-2"></i>

            {{ now()->locale('id')->translatedFormat(
                'l, d F Y'
            ) }}

        </div>

    </div>


    <div class="content-placeholder">

        <div class="content-placeholder-icon">
            <i class="bi bi-grid-1x2"></i>
        </div>

        <h5>Dashboard SI-LABKOM</h5>

        <p>
            Selamat datang,
            <strong>{{ auth()->user()->name }}</strong>.
        </p>

        <p class="mb-0">
            Konten statistik, grafik, jadwal,
            dan aktivitas laboratorium
            akan dikembangkan pada tahap berikutnya.
        </p>

    </div>

@endsection
