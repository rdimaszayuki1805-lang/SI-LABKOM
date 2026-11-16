
@extends('layouts.guru')

@section('title', $judul)

@section('content')

    <!-- HEADER HALAMAN -->
    <div class="dashboard-heading">

        <div>

            <h4 class="dashboard-title">
                {{ $judul }}
            </h4>

            <p class="dashboard-subtitle">
                Pengelolaan kegiatan laboratorium komputer
                MTs Al Ittihadiyah Pekanbaru.
            </p>

        </div>

    </div>


    <!-- KONTEN SEMENTARA -->
    <div class="content-placeholder">

        <div class="content-placeholder-icon">

            @switch($menuAktif)

                @case('jadwal')
                    <i class="bi bi-calendar-week"></i>
                    @break

                @case('pengajuan')
                    <i class="bi bi-file-earmark-plus"></i>
                    @break

                @case('logbook')
                    <i class="bi bi-journal-text"></i>
                    @break

                @default
                    <i class="bi bi-folder"></i>

            @endswitch

        </div>

        <h5>{{ $judul }}</h5>

        @if($menuAktif === 'jadwal')

            <p>
                Halaman ini akan digunakan oleh Guru
                untuk melihat jadwal penggunaan laboratorium.
            </p>

        @elseif($menuAktif === 'pengajuan')

            <p>
                Halaman ini akan digunakan oleh Guru
                untuk mengajukan penggunaan laboratorium
                dan memantau status persetujuan.
            </p>

        @elseif($menuAktif === 'logbook')

            <p>
                Halaman ini akan digunakan oleh Guru
                untuk memantau logbook siswa
                pada kelas yang ditangani.
            </p>

        @endif

        <a
            href="{{ route('guru.dashboard') }}"
            class="btn btn-success"
            style="background:#2F855A;border-color:#2F855A;">

            <i class="bi bi-arrow-left me-1"></i>

            Kembali ke Dashboard

        </a>

    </div>

@endsection
