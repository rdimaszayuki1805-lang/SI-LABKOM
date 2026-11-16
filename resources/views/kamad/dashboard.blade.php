
@extends('layouts.kamad')

@section('title', 'Dashboard Kepala Madrasah')

@section('content')

    <!-- ==========================================
         JUDUL DASHBOARD
    ========================================== -->

    <div class="dashboard-heading">

        <div>

            <h4 class="dashboard-title">
                Dashboard Kepala Madrasah
            </h4>

            <p class="dashboard-subtitle">
                Ringkasan kondisi laboratorium komputer
                MTs Al Ittihadiyah Pekanbaru.
            </p>

        </div>

        <div class="text-muted small">

            <i class="bi bi-calendar3 me-2"></i>

            {{ now()->locale('id')->translatedFormat('l, d F Y') }}

        </div>

    </div>


    <!-- ==========================================
         KONTEN SEMENTARA
    ========================================== -->

    <div class="content-placeholder">

        <div class="content-placeholder-icon">

            <i class="bi bi-building-check"></i>

        </div>

        <h5>
            Selamat Datang di Dashboard Kepala Madrasah
        </h5>

        <p>
            Halo,
            <strong>{{ auth()->user()->name }}</strong>.
        </p>

        <p>
            Anda dapat memantau informasi inventaris,
            penggunaan laboratorium, kondisi perangkat,
            kendala, dan laporan laboratorium melalui
            menu yang tersedia pada sidebar.
        </p>

        <p class="mb-0">

            Statistik dan grafik monitoring laboratorium
            akan ditampilkan setelah modul pengelolaan
            data selesai dikembangkan.

        </p>

    </div>

@endsection
