@extends('layouts.pjlab')

@section('title', 'Operasional Laboratorium')

@section('content')

    <style>
        {!! file_get_contents(resource_path('css/operasional.css')) !!}
    </style>

    @php
        $statusFilter = request('status', '');

        $statusPilihan = [
            '' => 'Semua',
            'menunggu' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];

        $daftarPengajuan = $pengajuan->filter(function ($item) use ($statusFilter) {
            return $statusFilter === '' || $item->status === $statusFilter;
        });

        $namaKomponen = [
            'monitor' => 'Monitor',
            'keyboard' => 'Keyboard',
            'mouse' => 'Mouse',
            'cpu' => 'CPU',
            'jaringan' => 'Internet',
        ];
    @endphp

    <div class="operasional-page">

        <!-- =====================================================
             JUDUL
        ===================================================== -->

        <div class="op-heading">

            <h4>Operasional Laboratorium</h4>

            <p>
                Kelola pengajuan, jadwal mata pelajaran,
                checklist kesiapan, dan monitoring logbook
                laboratorium.
            </p>

        </div>


        <!-- PESAN BERHASIL -->
        @if (session('success'))
            <div class="op-alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- PESAN PERINGATAN -->
        @if (session('warning'))
            <div class="op-alert warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('warning') }}
            </div>
        @endif

        <!-- ERROR VALIDASI -->
        @if ($errors->any())
            <div class="op-alert error">

                <strong>Periksa kembali data berikut:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        <!-- =====================================================
             TAB NAVIGASI
        ===================================================== -->

        <nav class="op-tabs" aria-label="Menu Operasional">

            <a href="{{ route('pjlab.operasional.index', ['tab' => 'pengajuan']) }}"
                class="op-tab {{ $tab === 'pengajuan' ? 'active' : '' }}">

                <i class="bi bi-file-earmark-text"></i>

                Pengajuan Peminjaman Lab-Kom
            </a>

            <a href="{{ route('pjlab.operasional.index', ['tab' => 'jadwal']) }}"
                class="op-tab {{ $tab === 'jadwal' ? 'active' : '' }}">

                <i class="bi bi-calendar3"></i>

                Jadwal Mata Pelajaran Lab-Kom
            </a>

            <a href="{{ route('pjlab.operasional.index', ['tab' => 'checklist']) }}"
                class="op-tab {{ $tab === 'checklist' ? 'active' : '' }}">

                <i class="bi bi-list-check"></i>

                Checklist Kesiapan
            </a>

            <a href="{{ route('pjlab.operasional.index', ['tab' => 'logbook']) }}"
                class="op-tab {{ $tab === 'logbook' ? 'active' : '' }}">

                <i class="bi bi-journal-check"></i>

                Monitoring Logbook
            </a>

        </nav>


        <!-- =====================================================
             TAB 1 : PENGAJUAN PEMINJAMAN
        ===================================================== -->

        @if ($tab === 'pengajuan')

            <div class="op-chip-list">

                @foreach ($statusPilihan as $key => $label)
                    <a href="{{ route('pjlab.operasional.index', [
                        'tab' => 'pengajuan',
                        'status' => $key,
                    ]) }}"
                        class="op-chip {{ $statusFilter === $key ? 'active' : '' }}">

                        {{ $label }}

                    </a>
                @endforeach

            </div>


            <div class="op-grid">

                @forelse($daftarPengajuan as $item)
                    <article class="op-card">

                        <div
                            class="d-flex justify-content-between
                                align-items-start gap-2 mb-3">

                            <div>
                                <div class="op-request-title">
                                    {{ $item->kelas }} -
                                    {{ $item->mata_pelajaran }}
                                </div>

                                <div class="op-small">
                                    {{ $item->guru?->name ?? 'Guru tidak tersedia' }}
                                </div>
                            </div>

                            <span class="op-status {{ $item->status }}">
                                {{ ucfirst($item->status) }}
                            </span>

                        </div>


                        <div class="op-small mb-2">

                            <i class="bi bi-calendar3 me-2"></i>

                            {{ $item->tanggal?->locale('id')->translatedFormat('d F Y') }}

                            ·

                            {{ substr($item->jam_mulai, 0, 5) }}
                            -
                            {{ substr($item->jam_selesai, 0, 5) }} WIB

                        </div>


                        <div class="op-small mb-2">

                            <i class="bi bi-people me-2"></i>

                            {{ $jumlahSiswa[$item->kelas] ?? 0 }}
                            siswa terdaftar

                        </div>


                        <div class="op-small mb-3">

                            <i class="bi bi-card-text me-2"></i>

                            {{ $item->tujuan }}

                        </div>


                        @if ($item->status === 'menunggu')
                            <div class="op-request-actions">

                                <!-- SETUJUI -->
                                <form method="POST" action="{{ route('pjlab.operasional.keputusan', $item) }}"
                                    onsubmit="return confirm('Setujui pengajuan ini?')">

                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="keputusan" value="disetujui">

                                    <button type="submit" class="op-btn">

                                        <i class="bi bi-check-lg"></i>
                                        Setujui

                                    </button>

                                </form>


                                <!-- TOLAK -->
                                <form method="POST" action="{{ route('pjlab.operasional.keputusan', $item) }}"
                                    onsubmit="return confirm('Tolak pengajuan ini?')">

                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="keputusan" value="ditolak">

                                    <button type="submit" class="op-btn op-btn-danger">

                                        <i class="bi bi-x-lg"></i>
                                        Tolak

                                    </button>

                                </form>

                            </div>
                        @else
                            <p class="op-small mb-0">
                                <i class="bi bi-check2-circle me-1"></i>
                                Pengajuan sudah diproses.
                            </p>
                        @endif

                    </article>

                @empty

                    <div class="op-card">

                        <div class="op-empty">
                            <i class="bi bi-inbox"></i>
                            Tidak ada pengajuan peminjaman.
                        </div>

                    </div>
                @endforelse

            </div>

        @endif


        <!-- =====================================================
             TAB 2 : JADWAL MATA PELAJARAN
        ===================================================== -->

        @if ($tab === 'jadwal')

            <section class="op-card">

                <div class="op-section-header">

                    <div>
                        <h5>
                            <i class="bi bi-calendar-week me-2 text-success"></i>
                            Jadwal Mata Pelajaran Laboratorium Komputer
                        </h5>

                        <div class="op-small">
                            Jadwal penggunaan laboratorium berdasarkan
                            mata pelajaran.
                        </div>
                    </div>


                    <button type="button" class="op-btn"
                        onclick="document.getElementById('modalTambahJadwal').showModal()">

                        <i class="bi bi-plus-lg"></i>

                        Tambah Jadwal Mata Pelajaran

                    </button>

                </div>


                <div class="op-grid">

                    @foreach ($daftarHari as $hari)
                        <div class="op-day-card">

                            <div class="op-day-title">

                                <i class="bi bi-calendar-day me-2"></i>

                                {{ $hari }}

                            </div>


                            @forelse($jadwal->get($hari, collect()) as $item)
                                <div class="op-schedule">

                                    <div
                                        class="d-flex
                                            justify-content-between
                                            align-items-center
                                            flex-wrap gap-2 mb-2">

                                        <span class="op-schedule-time">
                                            {{ substr($item->jam_mulai, 0, 5) }}
                                            -
                                            {{ substr($item->jam_selesai, 0, 5) }}
                                        </span>

                                        <span
                                            class="op-status
                                        {{ $item->status === 'Selesai' ? 'disetujui' : 'menunggu' }}">

                                            {{ $item->status }}

                                        </span>

                                    </div>

                                    <strong style="font-size:13px;">
                                        {{ $item->kelas }} -
                                        {{ $item->mata_pelajaran }}
                                    </strong>

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


            <!-- MODAL TAMBAH JADWAL -->
            <dialog id="modalTambahJadwal" class="op-dialog">

                <div class="op-dialog-header">

                    <div>
                        <h5>Tambah Jadwal Mata Pelajaran</h5>
                        <div class="op-small mt-1">
                            Lengkapi informasi jadwal laboratorium.
                        </div>
                    </div>

                    <button type="button" class="op-btn op-btn-outline"
                        onclick="document.getElementById('modalTambahJadwal').close()">
                        <i class="bi bi-x-lg"></i>
                    </button>

                </div>


                <form method="POST" action="{{ route('pjlab.operasional.jadwal.store') }}">

                    @csrf

                    <div class="op-two-cols">

                        <div class="op-field">
                            <label class="op-label" for="jadwalHari">
                                Hari *
                            </label>

                            <select name="hari" id="jadwalHari" class="op-input" required>

                                <option value="">Pilih Hari</option>

                                @foreach ($daftarHari as $hari)
                                    <option value="{{ $hari }}" @selected(old('hari') === $hari)>
                                        {{ $hari }}
                                    </option>
                                @endforeach

                            </select>
                        </div>


                        <div class="op-field">
                            <label class="op-label" for="jadwalKelas">
                                Kelas *
                            </label>

                            <select name="kelas" id="jadwalKelas" class="op-input" required>

                                <option value="">Pilih Kelas</option>

                                @foreach ($daftarKelas as $kelas)
                                    <option value="{{ $kelas }}" @selected(old('kelas') === $kelas)>
                                        {{ $kelas }}
                                    </option>
                                @endforeach

                            </select>
                        </div>


                        <div class="op-field">
                            <label class="op-label" for="jadwalMulai">
                                Jam Mulai *
                            </label>

                            <input type="time" name="jam_mulai" id="jadwalMulai" class="op-input"
                                value="{{ old('jam_mulai') }}" required>
                        </div>


                        <div class="op-field">
                            <label class="op-label" for="jadwalSelesai">
                                Jam Selesai *
                            </label>

                            <input type="time" name="jam_selesai" id="jadwalSelesai" class="op-input"
                                value="{{ old('jam_selesai') }}" required>
                        </div>


                        <div class="op-field">
                            <label class="op-label" for="jadwalMapel">
                                Mata Pelajaran *
                            </label>

                            <input type="text" name="mata_pelajaran" id="jadwalMapel" class="op-input"
                                maxlength="100" value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Informatika"
                                required>
                        </div>


                        <div class="op-field">
                            <label class="op-label" for="jadwalGuru">
                                Guru *
                            </label>

                            <input type="text" name="guru" id="jadwalGuru" class="op-input" maxlength="150"
                                value="{{ old('guru') }}" placeholder="Nama guru mata pelajaran" required>
                        </div>


                        <div class="op-field">
                            <label class="op-label" for="jadwalLokasi">
                                Laboratorium *
                            </label>

                            <select name="lokasi" id="jadwalLokasi" class="op-input" required>

                                <option value="">
                                    Pilih Laboratorium
                                </option>

                                @foreach ($daftarLokasi as $lokasi)
                                    <option value="{{ $lokasi }}" @selected(old('lokasi') === $lokasi)>
                                        {{ $lokasi }}
                                    </option>
                                @endforeach

                            </select>
                        </div>


                        <div class="op-field">
                            <label class="op-label" for="jadwalStatus">
                                Status *
                            </label>

                            <select name="status" id="jadwalStatus" class="op-input" required>

                                @foreach (['Belum Dimulai', 'Berlangsung', 'Selesai'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', 'Belum Dimulai') === $status)>
                                        {{ $status }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>


                    <div class="d-flex justify-content-end gap-2 mt-3">

                        <button type="button" class="op-btn op-btn-outline"
                            onclick="document.getElementById('modalTambahJadwal').close()">
                            Batal
                        </button>

                        <button type="submit" class="op-btn">
                            <i class="bi bi-save"></i>
                            Simpan Jadwal
                        </button>

                    </div>

                </form>

            </dialog>

        @endif


        <!-- =====================================================
             TAB 3 : CHECKLIST KESIAPAN
        ===================================================== -->

        @if ($tab === 'checklist')

            <div class="op-two-cols">

                <!-- FORM CHECKLIST -->
                <section class="op-card">

                    <div class="op-section-header">
                        <h5>
                            <i class="bi bi-list-check me-2 text-success"></i>
                            Checklist Kesiapan Laboratorium
                        </h5>
                    </div>


                    <form action="{{ route('pjlab.operasional.checklist.store') }}" method="POST">

                        @csrf


                        <!-- LOKASI CHECKLIST KESIAPAN -->
                        <div class="op-field">

                            <label class="op-label">
                                Lokasi Laboratorium
                            </label>

                            <input type="text" class="op-input" value="Lab Komputer 1" readonly>

                            <input type="hidden" name="lokasi" value="Lab Komputer 1">

                        </div>



                        @foreach ($itemChecklist as $komponen)
                            <div class="op-check-row">

                                <strong style="font-size:13px;">
                                    {{ $komponen }}
                                </strong>

                                <div class="d-flex gap-2 flex-wrap">

                                    <label class="op-choice">
                                        <input type="radio" name="hasil[{{ $komponen }}]" value="Siap"
                                            @checked(old("hasil.{$komponen}", 'Siap') === 'Siap') required>

                                        Siap
                                    </label>

                                    <label class="op-choice">
                                        <input type="radio" name="hasil[{{ $komponen }}]" value="Tidak Siap"
                                            @checked(old("hasil.{$komponen}") === 'Tidak Siap') required>

                                        Tidak Siap
                                    </label>

                                </div>

                            </div>
                        @endforeach


                        <button type="submit" class="op-btn w-100 mt-4">

                            <i class="bi bi-save"></i>

                            Simpan Checklist

                        </button>

                    </form>

                </section>


                <!-- RIWAYAT CHECKLIST -->
                <section class="op-card">

                    <div class="op-section-header">

                        <h5>
                            <i class="bi bi-clock-history me-2 text-success"></i>
                            Riwayat Checklist
                        </h5>

                    </div>


                    @forelse($riwayatChecklist as $item)
                        <div class="op-schedule mb-3">

                            <div
                                class="d-flex
                                    justify-content-between
                                    align-items-start gap-2">

                                <div>

                                    <strong style="font-size:13px;">
                                        {{ $item->lokasi }}
                                    </strong>

                                    <div class="op-small">
                                        {{ $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                        WIB
                                    </div>

                                    <div class="op-small">
                                        Oleh:
                                        {{ $item->pjlab?->name ?? 'PJ Lab' }}
                                    </div>

                                </div>

                                <span
                                    class="op-status
                                {{ $item->status === 'Siap' ? 'siap' : 'tidak-siap' }}">
                                    {{ $item->status }}
                                </span>

                            </div>


                            @if ($item->status === 'Tidak Siap')
                                <div class="op-small mt-2">

                                    <strong>Item tidak siap:</strong>

                                    @foreach ($item->hasil ?? [] as $nama => $kondisi)
                                        @if ($kondisi === 'Tidak Siap')
                                            <div>
                                                <i class="bi bi-exclamation-circle text-danger"></i>
                                                {{ $nama }}
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                            @endif

                        </div>

                    @empty

                        <div class="op-empty">
                            <i class="bi bi-clipboard"></i>
                            Belum ada riwayat checklist.
                        </div>
                    @endforelse

                </section>

            </div>

        @endif


        <!-- =====================================================
             TAB 4 : MONITORING LOGBOOK
        ===================================================== -->

        @if ($tab === 'logbook')

            <!-- FILTER: HANYA TANGGAL DAN KELAS -->

            <section class="op-card mb-3">

                <form method="GET" action="{{ route('pjlab.operasional.index') }}">

                    <input type="hidden" name="tab" value="logbook">

                    <div class="op-two-cols">

                        <!-- FILTER TANGGAL -->
                        <div>

                            <label for="filterTanggal" class="op-label">
                                Filter Tanggal
                            </label>

                            <input type="date" name="tanggal" id="filterTanggal" class="op-input"
                                value="{{ $filterTanggal }}">

                        </div>


                        <!-- FILTER KELAS -->
                        <div>

                            <label for="filterKelas" class="op-label">
                                Filter Kelas
                            </label>

                            <select name="kelas" id="filterKelas" class="op-input">

                                <option value="">
                                    Semua Kelas
                                </option>

                                @foreach ($daftarKelas as $kelas)
                                    <option value="{{ $kelas }}" @selected($filterKelas === $kelas)>

                                        Kelas {{ $kelas }}

                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>


                    <div class="d-flex flex-wrap gap-2 mt-3">

                        <button type="submit" class="op-btn">

                            <i class="bi bi-funnel"></i>

                            Terapkan Filter

                        </button>

                        <a href="{{ route('pjlab.operasional.index', ['tab' => 'logbook']) }}"
                            class="op-btn op-btn-outline">

                            <i class="bi bi-arrow-counterclockwise"></i>

                            Reset

                        </a>

                    </div>

                </form>

            </section>


            <!-- TABEL MONITORING -->
            <section class="op-card" style="padding:0;overflow:hidden;">

                <div class="op-table-wrap">

                    <table class="op-table">

                        <thead>

                            <tr>
                                <th>Nama</th>
                                <th>PC</th>
                                <th>Monitor</th>
                                <th>Keyboard</th>
                                <th>Mouse</th>
                                <th>CPU</th>
                                <th>Internet</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>

                        </thead>


                        <tbody>

                            @forelse($logbooks as $log)
                                <tr>

                                    <td>
                                        <strong>
                                            {{ $log->siswa?->nama ?? 'Siswa terhapus' }}
                                        </strong>

                                        <div class="op-small">
                                            {{ $log->siswa?->kelas ?? '-' }}
                                        </div>

                                        <div class="op-small">
                                            {{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                            WIB
                                        </div>
                                    </td>

                                    <td>
                                        {{ $log->nomor_komputer }}
                                    </td>


                                    @foreach (['monitor', 'keyboard', 'mouse', 'cpu', 'jaringan'] as $komponen)
                                        <td>

                                            @php
                                                $nilai = $log->{$komponen};
                                            @endphp

                                            @if ($nilai)
                                                <span
                                                    class="op-status
                                                {{ $nilai === 'Baik' ? 'baik' : 'rusak' }}">

                                                    {{ $nilai }}

                                                </span>
                                            @else
                                                <span class="op-small">-</span>
                                            @endif

                                        </td>
                                    @endforeach


                                    <td style="max-width:180px;">

                                        {{ \Illuminate\Support\Str::limit($log->kendala ?: 'Tidak ada kendala', 80) }}

                                    </td>


                                    <!-- DETAIL -->
                                    <td>

                                        <button type="button" class="op-btn op-btn-outline"
                                            aria-label="Lihat detail logbook"
                                            onclick="document.getElementById('detailLog{{ $log->id }}').showModal()">

                                            <i class="bi bi-eye"></i>

                                        </button>


                                        <dialog id="detailLog{{ $log->id }}" class="op-dialog">

                                            <div class="op-dialog-header">

                                                <h5>Detail Logbook</h5>

                                                <button type="button" class="op-btn op-btn-outline"
                                                    onclick="document.getElementById('detailLog{{ $log->id }}').close()">

                                                    <i class="bi bi-x-lg"></i>

                                                </button>

                                            </div>


                                            <table class="op-table">

                                                <tbody>

                                                    <tr>
                                                        <th>Tanggal Pengisian</th>
                                                        <td>
                                                            {{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}
                                                            WIB
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Nama Siswa</th>
                                                        <td>{{ $log->siswa?->nama ?? '-' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>NISN</th>
                                                        <td>{{ $log->siswa?->nisn ?? '-' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Kelas</th>
                                                        <td>{{ $log->siswa?->kelas ?? '-' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Mata Pelajaran</th>
                                                        <td>{{ $log->mata_pelajaran ?? '-' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th>Jam Login</th>
                                                        <td>
                                                            {{ $log->waktu_login ? $log->waktu_login->timezone('Asia/Jakarta')->format('H:i:s') : '-' }}
                                                            WIB
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Nomor PC</th>
                                                        <td>{{ $log->nomor_komputer }}</td>
                                                    </tr>

                                                    @foreach ($namaKomponen as $kolom => $nama)
                                                        <tr>
                                                            <th>{{ $nama }}</th>
                                                            <td>
                                                                {{ $log->{$kolom} ?? '-' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <th>Keterangan Kendala</th>
                                                        <td>
                                                            {{ $log->kendala ?: 'Tidak ada kendala' }}
                                                        </td>
                                                    </tr>

                                                </tbody>

                                            </table>

                                            <div class="d-flex justify-content-end mt-3">

                                                <button type="button" class="op-btn op-btn-outline"
                                                    onclick="document.getElementById('detailLog{{ $log->id }}').close()">

                                                    Tutup

                                                </button>

                                            </div>

                                        </dialog>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9">

                                        <div class="op-empty">

                                            <i class="bi bi-inbox"></i>

                                            Belum ada data Logbook
                                            untuk filter yang dipilih.

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>


            <!-- PAGINASI -->
            @if ($logbooks->hasPages())

                <div class="op-page-nav">

                    @if ($logbooks->onFirstPage())
                        <span class="op-small">Halaman pertama</span>
                    @else
                        <a href="{{ $logbooks->previousPageUrl() }}" class="op-btn op-btn-outline">
                            <i class="bi bi-chevron-left"></i>
                            Sebelumnya
                        </a>
                    @endif


                    <span class="op-small">
                        Halaman {{ $logbooks->currentPage() }}
                        dari {{ $logbooks->lastPage() }}
                    </span>


                    @if ($logbooks->hasMorePages())
                        <a href="{{ $logbooks->nextPageUrl() }}" class="op-btn op-btn-outline">
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
