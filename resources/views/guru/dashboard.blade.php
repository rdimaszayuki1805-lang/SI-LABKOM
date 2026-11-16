
@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('content')

    <!-- HEADER DASHBOARD -->
    <div class="dashboard-heading">

        <div>

            <h4 class="dashboard-title">
                Dashboard Guru
            </h4>

            <p class="dashboard-subtitle">
                Pantau jadwal, pengajuan, dan logbook kelas Anda.
            </p>

        </div>

        <div class="text-muted small">

            <i class="bi bi-calendar3 me-2"></i>

            {{ now()->locale('id')->translatedFormat('l, d F Y') }}

        </div>

    </div>


    <!-- KONTEN DASHBOARD SEMENTARA -->
    <div class="content-placeholder">

        <div class="content-placeholder-icon">
            <i class="bi bi-mortarboard"></i>
        </div>

        <h5>
            Selamat Datang di SI-LABKOM
        </h5>

        <p>
            Halo,
            <strong>{{ auth()->user()->name }}</strong>.
        </p>

        <p class="mb-0">
            Dashboard Guru sudah terhubung dengan
            sistem autentikasi dan sidebar Laravel.
            Statistik jadwal, status pengajuan,
            dan logbook kelas akan ditambahkan
            pada tahap pengembangan berikutnya.
        </p>

    </div>

@endsection
