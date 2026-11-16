
@extends('layouts.pjlab')

@section('title', $judul)

@section('content')

    <div class="dashboard-heading">

        <div>

            <h4 class="dashboard-title">
                {{ $judul }}
            </h4>

            <p class="dashboard-subtitle">
                Pengelolaan data SI-LABKOM
                MTs Al Ittihadiyah Pekanbaru.
            </p>

        </div>

    </div>


    <div class="content-placeholder">

        <div class="content-placeholder-icon">

            @switch($menuAktif)

                @case('inventaris')
                    <i class="bi bi-hdd-stack"></i>
                    @break

                @case('operasional')
                    <i class="bi bi-calendar-week"></i>
                    @break

                @case('monitoring')
                    <i class="bi bi-clipboard-check"></i>
                    @break

                @case('logbook')
                    <i class="bi bi-journal-text"></i>
                    @break

                @case('laporan')
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    @break

                @case('pengguna')
                    <i class="bi bi-people"></i>
                    @break

                @case('pengguna-logbook')
                    <i class="bi bi-mortarboard"></i>
                    @break

                @default
                    <i class="bi bi-folder"></i>

            @endswitch

        </div>


        <h5>{{ $judul }}</h5>

        <p>
            Halaman ini sudah terhubung
            dengan sidebar PJ Lab.
        </p>

        <p>
            Fitur pengelolaan data akan
            ditambahkan pada tahap berikutnya.
        </p>

        <a
            href="{{ route('pjlab.dashboard') }}"
            class="btn btn-success"
            style="background:#2F855A;border-color:#2F855A;">

            <i class="bi bi-arrow-left me-1"></i>
            Kembali ke Dashboard

        </a>

    </div>

@endsection
