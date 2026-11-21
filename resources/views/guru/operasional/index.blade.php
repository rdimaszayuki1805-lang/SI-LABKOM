
@extends('layouts.guru')

@section('title', 'Operasional Laboratorium')

@section('content')

<style>
    {!! file_get_contents(resource_path('css/operasional.css')) !!}

    .operasional-page .guru-intro {
        background: linear-gradient(110deg, #eaf7ef, #fff);
        border: 1px solid #dbece1;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .operasional-page .guru-intro h4 {
        font-weight: 800;
        color: #224d37;
        margin-bottom: 5px;
    }

    .operasional-page .guru-intro p {
        font-size: 13px;
        color: #698174;
        margin-bottom: 0;
    }

    .operasional-page .guru-schedule-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .operasional-page .guru-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .operasional-page .guru-detail-item {
        background: #f7faf8;
        padding: 12px;
        border-radius: 9px;
    }

    .operasional-page .guru-detail-item small {
        color: #798b7e;
        display: block;
        margin-bottom: 4px;
    }

    .operasional-page .guru-detail-item strong {
        color: #283c30;
        font-size: 13px;
    }

    .operasional-page .guru-info-banner {
        background: #f0f8f3;
        color: #397052;
        border: 1px solid #d9ebdf;
        padding: 13px 15px;
        border-radius: 10px;
        font-size: 12px;
        margin-bottom: 20px;
    }

    .operasional-page .guru-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .operasional-page .guru-full {
        grid-column: 1 / -1;
    }

    @media(max-width: 1050px) {
        .operasional-page .guru-schedule-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media(max-width: 680px) {
        .operasional-page .guru-schedule-grid,
        .operasional-page .guru-detail-grid,
        .operasional-page .guru-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@php
    $tabPilihan = [
        'pengajuan-saya' => [
            'judul' => 'Pengajuan Saya',
            'icon' => 'bi-file-earmark-check',
        ],
        'ajukan' => [
            'judul' => 'Pengajuan Peminjaman',
            'icon' => 'bi-file-earmark-plus',
        ],
        'jadwal' => [
            'judul' => 'Jadwal Mata Pelajaran',
            'icon' => 'bi-calendar-week',
        ],
        'logbook' => [
            'judul' => 'Logbook Kelas',
            'icon' => 'bi-journal-text',
        ],
    ];
@endphp

<div class="operasional-page">

    <!-- JUDUL HALAMAN -->

    <div class="guru-intro">
        <h4>
            <i class="bi bi-pc-display-horizontal me-2"></i>
            Operasional Laboratorium
        </h4>

        <p>
            Ajukan peminjaman, pantau persetujuan,
            lihat jadwal pelajaran dan Logbook Siswa.
        </p>
    </div>


    <!-- NOTIFIKASI -->

    @if(session('success'))
        <div class="op-alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="op-alert warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('warning') }}
        </div>
    @endif

    @if($errors->any())
        <div class="op-alert error">
            <strong>Data belum sesuai:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <!-- EMPAT TAB -->

    <nav class="op-tabs" aria-label="Operasional Guru">

        @foreach($tabPilihan as $key => $pilihan)
            <a
                href="{{ route('guru.operasional.index', ['tab' => $key]) }}"
                class="op-tab {{ $tab === $key ? 'active' : '' }}">

                <i class="bi {{ $pilihan['icon'] }}"></i>
                {{ $pilihan['judul'] }}
            </a>
        @endforeach

    </nav>


    <!-- ================================================
         TAB 1: PENGAJUAN SAYA
    ================================================= -->

    @if($tab === 'pengajuan-saya')

        <section class="op-card mb-3">

            <div class="op-section-header">

                <div>
                    <h5>
                        <i class="bi bi-file-earmark-check me-2 text-success"></i>
                        Riwayat Pengajuan Saya
                    </h5>

                    <div class="op-small">
                        Lihat status permohonan penggunaan
                        laboratorium yang telah Anda kirim.
                    </div>
                </div>

                <a
                    href="{{ route('guru.operasional.index', ['tab' => 'ajukan']) }}"
                    class="op-btn">

                    <i class="bi bi-plus-lg"></i>
                    Buat Pengajuan
                </a>
            </div>

        </section>


        <div class="op-grid">

            @forelse($pengajuanSaya as $item)

                <article class="op-card">

                    <div class="d-flex justify-content-between
                                align-items-start gap-2 mb-3">

                        <div>
                            <div class="op-request-title">
                                {{ $item->mata_pelajaran }}
                            </div>

                            <div class="op-small">
                                Kelas {{ $item->kelas }}
                            </div>
                        </div>

                        <span class="op-status {{ $item->status }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>


                    <div class="op-small mb-2">

                        <i class="bi bi-calendar3 me-2"></i>

                        {{ $item->tanggal
                            ? $item->tanggal->locale('id')->translatedFormat('d F Y')
                            : '-' }}

                    </div>

                    <div class="op-small mb-2">

                        <i class="bi bi-clock me-2"></i>

                        {{ substr($item->jam_mulai, 0, 5) }}
                        -
                        {{ substr($item->jam_selesai, 0, 5) }}
                        WIB
                    </div>

                    <div class="op-small mb-3">

                        <i class="bi bi-card-text me-2"></i>
                        {{ $item->tujuan }}

                    </div>


                    @if($item->status === 'menunggu')

                        <div class="guru-info-banner mb-0">
                            <i class="bi bi-hourglass-split me-1"></i>
                            Menunggu keputusan PJ Lab.
                        </div>

                    @elseif($item->status === 'disetujui')

                        <div class="guru-info-banner mb-0">
                            <i class="bi bi-check-circle me-1"></i>
                            Pengajuan sudah disetujui PJ Lab.
                        </div>

                    @else

                        <div class="op-alert error mb-0">
                            <i class="bi bi-x-circle me-1"></i>
                            Pengajuan tidak disetujui PJ Lab.
                        </div>

                    @endif


                    @if($item->catatan_pjlab)
                        <div class="op-small mt-3">
                            <strong>Catatan PJ Lab:</strong><br>
                            {{ $item->catatan_pjlab }}
                        </div>
                    @endif

                </article>

            @empty

                <section class="op-card">

                    <div class="op-empty">

                        <i class="bi bi-inbox"></i>

                        Belum ada pengajuan peminjaman.

                        <div class="mt-3">
                            <a
                                href="{{ route('guru.operasional.index', ['tab' => 'ajukan']) }}"
                                class="op-btn">

                                <i class="bi bi-plus-lg"></i>
                                Ajukan Peminjaman
                            </a>
                        </div>

                    </div>

                </section>

            @endforelse

        </div>

    @endif


    <!-- ================================================
         TAB 2: PENGAJUAN PEMINJAMAN
    ================================================= -->

    @if($tab === 'ajukan')

        <section class="op-card">

            <div class="op-section-header">

                <div>
                    <h5>
                        <i class="bi bi-file-earmark-plus me-2 text-success"></i>
                        Form Pengajuan Peminjaman Laboratorium
                    </h5>

                    <p class="op-small mb-0">
                        Isi informasi penggunaan Lab Komputer 1.
                    </p>
                </div>

            </div>


            <div class="guru-info-banner">

                <i class="bi bi-info-circle me-1"></i>

                Pengajuan akan dikirim ke Dashboard PJ Lab
                untuk diperiksa dan disetujui.
                Jika email sistem sudah dikonfigurasi,
                pemberitahuan juga dikirim ke email PJ Lab.

            </div>


            <form
                action="{{ route('guru.operasional.pengajuan.store') }}"
                method="POST">

                @csrf

                <div class="guru-form-grid">

                    <!-- NAMA GURU -->
                    <div class="op-field">

                        <label class="op-label">
                            Nama Guru
                        </label>

                        <input
                            type="text"
                            class="op-input"
                            value="{{ auth()->user()->name }}"
                            readonly>

                    </div>


                    <!-- LOKASI -->
                    <div class="op-field">

                        <label class="op-label">
                            Laboratorium
                        </label>

                        <input
                            type="text"
                            class="op-input"
                            value="Lab Komputer 1"
                            readonly>

                    </div>


                    <!-- KELAS -->
                    <div class="op-field">

                        <label for="kelasPengajuan" class="op-label">
                            Kelas *
                        </label>

                        <select
                            name="kelas"
                            id="kelasPengajuan"
                            class="op-input"
                            required>

                            <option value="">Pilih Kelas</option>

                            @foreach($daftarKelas as $kelas)

                                <option
                                    value="{{ $kelas }}"
                                    @selected(old('kelas') === $kelas)>

                                    Kelas {{ $kelas }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- MATA PELAJARAN -->
                    <div class="op-field">

                        <label for="mapelPengajuan" class="op-label">
                            Mata Pelajaran *
                        </label>

                        <input
                            type="text"
                            name="mata_pelajaran"
                            id="mapelPengajuan"
                            class="op-input"
                            placeholder="Contoh: Informatika"
                            maxlength="100"
                            value="{{ old('mata_pelajaran') }}"
                            required>

                    </div>


                    <!-- TANGGAL -->
                    <div class="op-field">

                        <label for="tanggalPengajuan" class="op-label">
                            Tanggal Peminjaman *
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            id="tanggalPengajuan"
                            class="op-input"
                            min="{{ now()->format('Y-m-d') }}"
                            value="{{ old('tanggal') }}"
                            required>

                    </div>


                    <!-- JAM MULAI -->
                    <div class="op-field">

                        <label for="jamMulaiPengajuan" class="op-label">
                            Jam Mulai *
                        </label>

                        <input
                            type="time"
                            name="jam_mulai"
                            id="jamMulaiPengajuan"
                            class="op-input"
                            value="{{ old('jam_mulai') }}"
                            required>

                    </div>


                    <!-- JAM SELESAI -->
                    <div class="op-field">

                        <label for="jamSelesaiPengajuan" class="op-label">
                            Jam Selesai *
                        </label>

                        <input
                            type="time"
                            name="jam_selesai"
                            id="jamSelesaiPengajuan"
                            class="op-input"
                            value="{{ old('jam_selesai') }}"
                            required>

                    </div>


                    <!-- TUJUAN -->
                    <div class="op-field guru-full">

                        <label for="tujuanPengajuan" class="op-label">
                            Keperluan / Tujuan Penggunaan *
                        </label>

                        <textarea
                            name="tujuan"
                            id="tujuanPengajuan"
                            class="op-input"
                            rows="4"
                            maxlength="2000"
                            placeholder="Jelaskan kegiatan yang akan dilaksanakan di laboratorium..."
                            required>{{ old('tujuan') }}</textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-end flex-wrap gap-2 mt-3">

                    <a
                        href="{{ route('guru.operasional.index', ['tab' => 'pengajuan-saya']) }}"
                        class="op-btn op-btn-outline">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="op-btn"
                        onclick="return confirm('Kirim pengajuan peminjaman ke PJ Lab?')">

                        <i class="bi bi-send"></i>

                        Kirim Pengajuan

                    </button>

                </div>

            </form>

        </section>

    @endif


    <!-- ================================================
         TAB 3: JADWAL MATA PELAJARAN
         READ ONLY DARI PJ LAB
    ================================================= -->

    @if($tab === 'jadwal')

        <section class="op-card">

            <div class="op-section-header">

                <div>

                    <h5>
                        <i class="bi bi-calendar-week me-2 text-success"></i>
                        Jadwal Mata Pelajaran Laboratorium
                    </h5>

                    <div class="op-small">
                        Jadwal mingguan yang telah ditetapkan
                        oleh Penanggung Jawab Laboratorium.
                    </div>

                </div>

            </div>


            <div class="guru-info-banner">

                <i class="bi bi-eye me-1"></i>

                Jadwal ini hanya dapat dilihat.
                Penambahan dan perubahan jadwal dilakukan
                oleh PJ Lab.

            </div>


            <div class="guru-schedule-grid">

                @foreach($daftarHari as $hari)

                    <div class="op-day-card">

                        <div class="op-day-title">

                            <i class="bi bi-calendar-day me-2"></i>

                            {{ $hari }}

                        </div>


                        @forelse($jadwal->get($hari, collect()) as $item)

                            <div class="op-schedule">

                                <div class="d-flex justify-content-between
                                            align-items-center flex-wrap gap-2 mb-2">

                                    <span class="op-schedule-time">

                                        {{ substr($item->jam_mulai, 0, 5) }}
                                        -
                                        {{ substr($item->jam_selesai, 0, 5) }}

                                    </span>

                                    <span class="op-status
                                        {{ $item->status === 'Selesai'
                                            ? 'disetujui'
                                            : 'menunggu' }}">

                                        {{ $item->status }}

                                    </span>

                                </div>


                                <div class="op-request-title">

                                    {{ $item->kelas }}
                                    -
                                    {{ $item->mata_pelajaran }}

                                </div>


                                <div class="op-small mt-2">

                                    <i class="bi bi-person me-1"></i>

                                    {{ $item->guru }}

                                </div>


                                <div class="op-small">

                                    <i class="bi bi-geo-alt me-1"></i>

                                    {{ $item->lokasi }}

                                </div>

                            </div>

                        @empty

                            <div class="op-small">
                                <i>Belum ada jadwal.</i>
                            </div>

                        @endforelse

                    </div>

                @endforeach

            </div>

        </section>

    @endif


    <!-- ================================================
         TAB 4: LOGBOOK KELAS
    ================================================= -->

    @if($tab === 'logbook')

        <section class="op-card mb-3">

            <div class="op-section-header">

                <div>

                    <h5>
                        <i class="bi bi-journal-text me-2 text-success"></i>
                        Logbook Kelas
                    </h5>

                    <div class="op-small">
                        Monitoring hasil pengisian Logbook Siswa
                        berdasarkan kelas dan tanggal.
                    </div>

                </div>

            </div>


            <!-- FILTER -->
            <form
                action="{{ route('guru.operasional.index') }}"
                method="GET">

                <input
                    type="hidden"
                    name="tab"
                    value="logbook">

                <div class="guru-form-grid">

                    <!-- TANGGAL -->
                    <div class="op-field">

                        <label class="op-label" for="filterLogTanggal">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            class="op-input"
                            name="tanggal"
                            id="filterLogTanggal"
                            value="{{ $filterTanggal }}">

                    </div>


                    <!-- KELAS -->
                    <div class="op-field">

                        <label class="op-label" for="filterLogKelas">
                            Kelas
                        </label>

                        <select
                            name="kelas"
                            id="filterLogKelas"
                            class="op-input">

                            <option value="">Semua Kelas</option>

                            @foreach($daftarKelas as $kelas)

                                <option
                                    value="{{ $kelas }}"
                                    @selected($filterKelas === $kelas)>

                                    Kelas {{ $kelas }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="d-flex gap-2 flex-wrap">

                    <button type="submit" class="op-btn">

                        <i class="bi bi-funnel"></i>
                        Terapkan Filter

                    </button>

                    <a
                        href="{{ route('guru.operasional.index', ['tab' => 'logbook']) }}"
                        class="op-btn op-btn-outline">

                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset

                    </a>

                </div>

            </form>

        </section>


        <section class="op-card" style="padding:0;overflow:hidden;">

            <div class="op-table-wrap">

                <table class="op-table">

                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Nomor PC</th>
                            <th>Kondisi</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody>

                        @forelse($logbooks as $log)

                            <tr>

                                <td>
                                    {{ $log->created_at
                                        ->timezone('Asia/Jakarta')
                                        ->format('d/m/Y H:i') }}
                                    WIB
                                </td>

                                <td>
                                    <strong>
                                        {{ $log->siswa?->nama ?? '-' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $log->siswa?->kelas ?? '-' }}
                                </td>

                                <td>
                                    {{ $log->mata_pelajaran ?? '-' }}
                                </td>

                                <td>
                                    {{ $log->nomor_komputer }}
                                </td>

                                <td>

                                    @if($log->kondisi_perangkat === 'Baik')

                                        <span class="op-status baik">
                                            Baik
                                        </span>

                                    @else

                                        <span class="op-status rusak">
                                            Bermasalah
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit(
                                        $log->kendala ?: 'Tidak ada kendala',
                                        60
                                    ) }}
                                </td>

                                <td>

                                    <button
                                        type="button"
                                        class="op-btn op-btn-outline"
                                        onclick="document.getElementById('logGuru{{ $log->id }}').showModal()">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">

                                    <div class="op-empty">

                                        <i class="bi bi-inbox"></i>

                                        Belum ada Logbook untuk
                                        kelas dan tanggal yang dipilih.

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        <!-- DETAIL LOGBOOK: DI LUAR TABEL -->

        @foreach($logbooks as $log)

            <dialog
                id="logGuru{{ $log->id }}"
                class="op-dialog">

                <div class="op-dialog-header">

                    <h5>Detail Logbook Siswa</h5>

                    <button
                        type="button"
                        class="op-btn op-btn-outline"
                        onclick="document.getElementById('logGuru{{ $log->id }}').close()">

                        <i class="bi bi-x-lg"></i>

                    </button>

                </div>


                <div class="guru-detail-grid">

                    <div class="guru-detail-item">
                        <small>Nama Siswa</small>
                        <strong>
                            {{ $log->siswa?->nama ?? '-' }}
                        </strong>
                    </div>

                    <div class="guru-detail-item">
                        <small>NISN</small>
                        <strong>
                            {{ $log->siswa?->nisn ?? '-' }}
                        </strong>
                    </div>

                    <div class="guru-detail-item">
                        <small>Kelas</small>
                        <strong>
                            {{ $log->siswa?->kelas ?? '-' }}
                        </strong>
                    </div>

                    <div class="guru-detail-item">
                        <small>Mata Pelajaran</small>
                        <strong>
                            {{ $log->mata_pelajaran ?? '-' }}
                        </strong>
                    </div>

                    <div class="guru-detail-item">
                        <small>Nomor PC</small>
                        <strong>
                            {{ $log->nomor_komputer }}
                        </strong>
                    </div>

                    <div class="guru-detail-item">
                        <small>Waktu Pengisian</small>
                        <strong>
                            {{ $log->created_at
                                ->timezone('Asia/Jakarta')
                                ->format('d/m/Y H:i:s') }} WIB
                        </strong>
                    </div>

                    @foreach([
                        'monitor' => 'Monitor',
                        'keyboard' => 'Keyboard',
                        'mouse' => 'Mouse',
                        'cpu' => 'CPU',
                        'jaringan' => 'Jaringan / Internet',
                    ] as $key => $nama)

                        <div class="guru-detail-item">

                            <small>{{ $nama }}</small>

                            <strong>
                                {{ $log->{$key} ?? '-' }}
                            </strong>

                        </div>

                    @endforeach

                    <div class="guru-detail-item guru-full">

                        <small>Keterangan Kendala</small>

                        <strong>
                            {{ $log->kendala ?: 'Tidak ada kendala' }}
                        </strong>

                    </div>

                </div>


                <div class="d-flex justify-content-end mt-3">

                    <button
                        type="button"
                        class="op-btn op-btn-outline"
                        onclick="document.getElementById('logGuru{{ $log->id }}').close()">

                        Tutup

                    </button>

                </div>

            </dialog>

        @endforeach


        <!-- PAGINASI -->
        @if($logbooks->hasPages())

            <div class="op-page-nav">

                @if($logbooks->onFirstPage())
                    <span class="op-small">Halaman pertama</span>
                @else

                    <a
                        href="{{ $logbooks->previousPageUrl() }}"
                        class="op-btn op-btn-outline">

                        <i class="bi bi-chevron-left"></i>
                        Sebelumnya

                    </a>

                @endif


                <span class="op-small">
                    Halaman {{ $logbooks->currentPage() }}
                    dari {{ $logbooks->lastPage() }}
                </span>


                @if($logbooks->hasMorePages())

                    <a
                        href="{{ $logbooks->nextPageUrl() }}"
                        class="op-btn op-btn-outline">

                        Berikutnya
                        <i class="bi bi-chevron-right"></i>

                    </a>

                @else
                    <span class="op-small">Halaman terakhir</span>
                @endif

            </div>

        @endif

    @endif

</div>

@endsection
