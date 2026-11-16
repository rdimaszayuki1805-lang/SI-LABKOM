
@extends('layouts.kamad')

@section('title', $judul)

@section('content')

    <!-- ==========================================
         JUDUL HALAMAN
    ========================================== -->

    <div class="dashboard-heading">

        <div>

            <h4 class="dashboard-title">
                {{ $judul }}
            </h4>

            <p class="dashboard-subtitle">
                Monitoring laboratorium komputer
                MTs Al Ittihadiyah Pekanbaru.
            </p>

        </div>

    </div>


    <!-- ==========================================
         HALAMAN SEMENTARA
    ========================================== -->

    <div class="content-placeholder">

        <div class="content-placeholder-icon">

            @switch($menuAktif)

                @case('inventaris')
                    <i class="bi bi-pc-display"></i>
                    @break

                @case('penggunaan')
                    <i class="bi bi-calendar-check"></i>
                    @break

                @case('monitoring')
                    <i class="bi bi-exclamation-triangle"></i>
                    @break

                @case('laporan')
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    @break

                @default
                    <i class="bi bi-folder"></i>

            @endswitch

        </div>

        <h5>{{ $judul }}</h5>

        @if($menuAktif === 'inventaris')

            <p>
                Halaman ini akan menampilkan data inventaris
                perangkat laboratorium beserta kondisinya.
                Kepala Madrasah hanya dapat melihat data.
            </p>

        @elseif($menuAktif === 'penggunaan')

            <p>
                Halaman ini akan menampilkan jadwal
                dan riwayat penggunaan laboratorium.
                Kepala Madrasah hanya dapat melihat data.
            </p>

        @elseif($menuAktif === 'monitoring')

            <p>
                Halaman ini akan menampilkan informasi
                kendala dan pemeliharaan perangkat
                laboratorium komputer.
            </p>

        @elseif($menuAktif === 'laporan')

            <p>
                Halaman ini akan menampilkan laporan
                kondisi dan penggunaan laboratorium
                untuk kebutuhan pemantauan
                Kepala Madrasah.
            </p>

        @endif

        <a
            href="{{ route('kamad.dashboard') }}"
            class="btn btn-success"
            style="background:#2F855A;border-color:#2F855A;">

            <i class="bi bi-arrow-left me-1"></i>

            Kembali ke Dashboard

        </a>

    </div>

@endsection
