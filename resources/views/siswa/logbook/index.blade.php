
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Logbook Siswa | SI-LABKOM</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <style>
        {!! file_get_contents(resource_path('css/siswa.css')) !!}
    </style>
</head>

<body class="siswa-body">

    <!-- ==========================================
         HEADER DUA LOGO
    ========================================== -->

    <header class="siswa-header">

        <div class="siswa-container siswa-header-inner">

            <a
                href="{{ route('logbook.siswa') }}"
                class="siswa-brand">

                <div class="siswa-logos">

                    <img
                        src="{{ asset('images/logomts.png') }}"
                        alt="Logo MTs"
                        class="siswa-logo">

                    <div class="siswa-logo-divider"></div>

                    <img
                        src="{{ asset('images/logoykpi.jpg') }}"
                        alt="Logo YKPI"
                        class="siswa-logo">

                </div>

                <div>

                    <div class="siswa-brand-title">
                        SI-LABKOM MTS AL ITTIHAD
                    </div>

                    <div class="siswa-brand-subtitle">
                        Logbook Laboratorium Komputer
                    </div>

                </div>

            </a>

            <form
                action="{{ route('siswa.logout') }}"
                method="POST">

                @csrf

                <button
                    type="submit"
                    class="siswa-back">

                    <i class="bi bi-box-arrow-right"></i>

                    <span class="siswa-back-text">
                        Logout
                    </span>

                </button>

            </form>

        </div>

    </header>

    <!-- ==========================================
         KONTEN HALAMAN
    ========================================== -->

    <main class="siswa-container siswa-main">

        <div class="siswa-page-heading">

            <h1>
                Logbook Laboratorium Komputer
            </h1>

            <p>
                Selamat datang,
                <strong>{{ $siswa->nama }}</strong>.
                Silakan isi pemeriksaan kondisi perangkat
                sebelum menyimpan Logbook.
            </p>

        </div>

        <!-- PESAN BERHASIL -->
        @if(session('success'))

            <div class="siswa-alert siswa-alert-success">

                <i class="bi bi-check-circle"></i>

                {{ session('success') }}

            </div>

        @endif

        <!-- VALIDASI -->
        @if($errors->any())

            <div class="siswa-alert siswa-alert-danger">

                <strong>
                    Periksa kembali isian Logbook:
                </strong>

                <ul>

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

        <!-- ==========================================
             DATA OTOMATIS
        ========================================== -->

        <section class="siswa-card">

            <h2 class="siswa-card-title">

                <i class="bi bi-person-vcard"></i>

                Identitas Siswa dan Waktu Login

            </h2>

            <div class="siswa-grid">

                <div>
                    <label class="siswa-label">
                        Nama Siswa
                    </label>

                    <div class="siswa-readonly">
                        {{ $siswa->nama }}
                    </div>
                </div>

                <div>
                    <label class="siswa-label">
                        NISN
                    </label>

                    <div class="siswa-readonly">
                        {{ $siswa->nisn }}
                    </div>
                </div>

                <div>
                    <label class="siswa-label">
                        Kelas
                    </label>

                    <div class="siswa-readonly">
                        {{ $siswa->kelas }}
                    </div>
                </div>

                <div>
                    <label class="siswa-label">
                        Tanggal Login
                    </label>

                    <div class="siswa-readonly">

                        {{ $waktuLogin
                            ->locale('id')
                            ->translatedFormat('d F Y') }}

                    </div>
                </div>

                <div>
                    <label class="siswa-label">
                        Jam Login
                    </label>

                    <div class="siswa-readonly">

                        {{ $waktuLogin->format('H:i:s') }} WIB

                    </div>
                </div>

                <div>
                    <label class="siswa-label">
                        Jam Sekarang (Otomatis)
                    </label>

                    <div
                        id="jamSekarang"
                        class="siswa-readonly siswa-jam">

                        --:--:-- WIB

                    </div>
                </div>

            </div>

        </section>

        <!-- ==========================================
             FORM PENGISIAN LOGBOOK
        ========================================== -->

        <section class="siswa-card">

            <h2 class="siswa-card-title">

                <i class="bi bi-journal-text"></i>

                Form Pengisian Logbook

            </h2>

            <form
                action="{{ route('logbook.siswa.store') }}"
                method="POST"
                id="formLogbook">

                @csrf

                <!-- KELAS DAN MATA PELAJARAN -->
                <div class="siswa-grid">

                    <div>

                        <label class="siswa-label">
                            Kelas
                        </label>

                        <div class="siswa-readonly">
                            {{ $siswa->kelas }}
                        </div>

                    </div>

                    <div>

                        <label
                            for="mataPelajaran"
                            class="siswa-label">

                            Mata Pelajaran
                            <span style="color:#dc3545">*</span>

                        </label>

                        <select
                            id="mataPelajaran"
                            name="mata_pelajaran"
                            class="siswa-select"
                            required>

                            <option value="">
                                Pilih Mata Pelajaran
                            </option>

                            @foreach($daftarMapel as $mapel)

                                <option
                                    value="{{ $mapel }}"
                                    @selected(old('mata_pelajaran') === $mapel)>

                                    {{ $mapel }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- NOMOR PC -->
                    <div>

                        <label
                            for="nomorPc"
                            class="siswa-label">

                            Nomor PC
                            <span style="color:#dc3545">*</span>

                        </label>

                        <select
                            id="nomorPc"
                            name="nomor_komputer"
                            class="siswa-select"
                            required>

                            <option value="">
                                Pilih Nomor PC
                            </option>

                            @foreach($daftarPc as $pc)

                                <option
                                    value="{{ $pc }}"
                                    @selected(old('nomor_komputer') === $pc)>

                                    {{ $pc }}

                                </option>

                            @endforeach

                        </select>

                        <small class="logbook-help">
                            Pilih komputer yang sedang Anda gunakan.
                        </small>

                    </div>

                </div>

                <!-- ==================================
                     PEMERIKSAAN KONDISI
                ================================== -->

                <div class="logbook-section-title">

                    <i class="bi bi-clipboard-check"></i>

                    Pemeriksaan Kondisi Perangkat

                </div>

                @php
                    $perangkat = [
                        'monitor' => [
                            'nama' => 'Monitor',
                            'icon' => 'bi-display',
                        ],
                        'keyboard' => [
                            'nama' => 'Keyboard',
                            'icon' => 'bi-keyboard',
                        ],
                        'mouse' => [
                            'nama' => 'Mouse',
                            'icon' => 'bi-mouse',
                        ],
                        'cpu' => [
                            'nama' => 'CPU',
                            'icon' => 'bi-cpu',
                        ],
                        'jaringan' => [
                            'nama' => 'Jaringan / Internet',
                            'icon' => 'bi-wifi',
                        ],
                    ];
                @endphp

                <div class="logbook-condition-grid">

                    @foreach($perangkat as $key => $detail)

                        <div class="logbook-condition-card">

                            <div class="logbook-condition-heading">

                                <i class="bi {{ $detail['icon'] }}"></i>

                                {{ $detail['nama'] }}

                            </div>

                            <div class="logbook-radio-options">

                                <!-- BAIK -->
                                <label class="logbook-radio-label">

                                    <input
                                        type="radio"
                                        name="{{ $key }}"
                                        value="Baik"
                                        @checked(old($key, 'Baik') === 'Baik')
                                        required>

                                    <span class="logbook-radio-face">

                                        <i class="bi bi-check-circle"></i>

                                        Baik

                                    </span>

                                </label>

                                <!-- RUSAK -->
                                <label class="logbook-radio-label">

                                    <input
                                        type="radio"
                                        name="{{ $key }}"
                                        value="Rusak"
                                        @checked(old($key) === 'Rusak')
                                        required>

                                    <span class="logbook-radio-face">

                                        <i class="bi bi-x-circle"></i>

                                        Rusak

                                    </span>

                                </label>

                            </div>

                        </div>

                    @endforeach

                </div>

                <!-- ==================================
                     KENDALA PENGGUNAAN
                ================================== -->

                <div class="logbook-section-title">

                    <i class="bi bi-exclamation-triangle"></i>

                    Kendala Penggunaan

                </div>

                <label class="siswa-label">

                    Apakah terdapat kendala saat
                    menggunakan perangkat?

                    <span style="color:#dc3545">*</span>

                </label>

                <div class="logbook-kendala-options">

                    <label class="logbook-radio-label">

                        <input
                            type="radio"
                            name="ada_kendala"
                            value="0"
                            @checked(old('ada_kendala', '0') === '0')
                            required>

                        <span class="logbook-radio-face">

                            <i class="bi bi-check-circle"></i>

                            Tidak

                        </span>

                    </label>

                    <label class="logbook-radio-label">

                        <input
                            type="radio"
                            name="ada_kendala"
                            value="1"
                            @checked(old('ada_kendala') === '1')
                            required>

                        <span class="logbook-radio-face">

                            <i class="bi bi-exclamation-circle"></i>

                            Ya

                        </span>

                    </label>

                </div>

                <!-- KETERANGAN KENDALA -->
                <div
                    class="logbook-kendala-description"
                    id="areaKeterangan">

                    <label
                        for="keteranganKendala"
                        class="siswa-label">

                        Keterangan Kendala

                        <span
                            id="tandaWajibKendala"
                            style="color:#dc3545">

                            *

                        </span>

                    </label>

                    <textarea
                        id="keteranganKendala"
                        name="kendala"
                        class="siswa-textarea"
                        maxlength="2000"
                        placeholder="Jelaskan kendala yang Anda alami atau temukan...">{{ old('kendala') }}</textarea>

                </div>

                <!-- SIMPAN -->
                <button
                    type="submit"
                    class="siswa-button siswa-button-full"
                    style="margin-top:28px;">

                    <i class="bi bi-floppy"></i>

                    Simpan Logbook

                </button>

                <p class="logbook-submit-note">

                    <i class="bi bi-info-circle"></i>

                    Pastikan nomor PC dan kondisi perangkat
                    telah sesuai. Jika ditemukan kerusakan,
                    laporan akan tersimpan untuk ditindaklanjuti
                    Penanggung Jawab Laboratorium.

                </p>

            </form>

        </section>

        <!-- ==========================================
             RIWAYAT LOGBOOK
        ========================================== -->

        <section class="siswa-card">

            <h2 class="siswa-card-title">

                <i class="bi bi-clock-history"></i>

                Riwayat Logbook Saya

            </h2>

            <div class="siswa-table-wrap">

                <table class="siswa-table">

                    <thead>

                        <tr>
                            <th>Waktu</th>
                            <th>Mata Pelajaran</th>
                            <th>Nomor PC</th>
                            <th>Kondisi</th>
                            <th>Kendala</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($riwayat as $item)

                            <tr>

                                <td>

                                    {{ $item->created_at
                                        ->timezone('Asia/Jakarta')
                                        ->format('d/m/Y H:i') }}

                                    WIB

                                </td>

                                <td>
                                    {{ $item->mata_pelajaran ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->nomor_komputer }}
                                </td>

                                <td>
                                    {{ $item->kondisi_perangkat }}
                                </td>

                                <td>
                                    {{ $item->kendala ?: 'Tidak ada' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5">

                                    <div class="siswa-empty">

                                        Belum ada Logbook
                                        yang disimpan.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </main>

    <!-- ==========================================
         JAVASCRIPT JAM OTOMATIS DAN KENDALA
    ========================================== -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            | JAM DIGITAL WIB
            */

            const jam = document.getElementById('jamSekarang');

            const formatter = new Intl.DateTimeFormat(
                'id-ID',
                {
                    timeZone: 'Asia/Jakarta',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hourCycle: 'h23'
                }
            );

            function updateJam() {

                jam.textContent =
                    formatter.format(new Date()) + ' WIB';

            }

            updateJam();

            setInterval(updateJam, 1000);

            /*
            | KETERANGAN KENDALA
            */

            const pilihanKendala = document.querySelectorAll(
                'input[name="ada_kendala"]'
            );

            const areaKeterangan = document.getElementById(
                'areaKeterangan'
            );

            const inputKeterangan = document.getElementById(
                'keteranganKendala'
            );

            const form = document.getElementById('formLogbook');

            const pilihanPerangkat = document.querySelectorAll(
                'input[name="monitor"],' +
                'input[name="keyboard"],' +
                'input[name="mouse"],' +
                'input[name="cpu"],' +
                'input[name="jaringan"]'
            );

            function adaPerangkatRusak() {

                return Array.from(pilihanPerangkat).some(
                    input => input.checked && input.value === 'Rusak'
                );

            }

            function updateKeterangan() {

                const pilihan = document.querySelector(
                    'input[name="ada_kendala"]:checked'
                );

                const adaKendala =
                    pilihan && pilihan.value === '1';

                // Keterangan hanya tampil saat memilih Ya.
                areaKeterangan.hidden = !adaKendala;

                inputKeterangan.required = Boolean(adaKendala);

                inputKeterangan.disabled = !adaKendala;

            }

            pilihanKendala.forEach(function (input) {

                input.addEventListener(
                    'change',
                    updateKeterangan
                );

            });

            pilihanPerangkat.forEach(function (input) {

                input.addEventListener('change', function () {

                    if (adaPerangkatRusak()) {

                        const pilihanYa = document.querySelector(
                            'input[name="ada_kendala"][value="1"]'
                        );

                        pilihanYa.checked = true;

                    }

                    updateKeterangan();

                });

            });

            form.addEventListener('submit', function (event) {

                if (adaPerangkatRusak()) {

                    const pilihan = document.querySelector(
                        'input[name="ada_kendala"]:checked'
                    );

                    if (!pilihan || pilihan.value !== '1') {

                        event.preventDefault();

                        alert(
                            'Ada perangkat yang dipilih Rusak. ' +
                            'Pilih Ya pada kendala penggunaan.'
                        );

                    }

                }

            });

            updateKeterangan();

        });
    </script>

</body>
</html>
