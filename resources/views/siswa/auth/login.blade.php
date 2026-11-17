
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Login Siswa | SI-LABKOM</title>

    <!-- BOOTSTRAP ICONS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- CSS KHUSUS SISWA -->
    <style>
        {!! file_get_contents(resource_path('css/siswa.css')) !!}
    </style>
</head>

<body class="siswa-body">

    <!-- HEADER -->
    <header class="siswa-header">

        <div class="siswa-container siswa-header-inner">

            <!-- BRAND -->
            <a
                href="{{ route('landing') }}"
                class="siswa-brand">

                <div class="siswa-logos">

                    <img
                        src="{{ asset('images/logomts.png') }}"
                        alt="Logo MTs Al Ittihadiyah"
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
                        Sistem Informasi Pengelolaan
                        Laboratorium Komputer
                    </div>

                </div>

            </a>

            <!-- BERANDA -->
            <a
                href="{{ route('landing') }}"
                class="siswa-back">

                <i class="bi bi-house"></i>

                <span class="siswa-back-text">
                    Beranda
                </span>

            </a>

        </div>

    </header>

    <!-- HALAMAN LOGIN -->
    <main class="siswa-login-section">

        <div class="siswa-login-wrapper">

            <div class="siswa-login-heading">

                <div class="siswa-login-icon">
                    <i class="bi bi-person-badge"></i>
                </div>

                <h1>Login Siswa</h1>

                <p>
                    Masukkan NISN dan password
                    yang telah diberikan PJ Lab
                    untuk mengisi Logbook Laboratorium.
                </p>

            </div>

            <div class="siswa-card">

                <!-- SUCCESS -->
                @if(session('success'))

                    <div class="siswa-alert siswa-alert-success">
                        {{ session('success') }}
                    </div>

                @endif

                <!-- ERROR -->
                @if(session('error'))

                    <div class="siswa-alert siswa-alert-danger">
                        {{ session('error') }}
                    </div>

                @endif

                <!-- VALIDASI -->
                @if($errors->any())

                    <div class="siswa-alert siswa-alert-danger">

                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach

                    </div>

                @endif

                <!-- FORM LOGIN -->
                <form
                    action="{{ route('siswa.login.process') }}"
                    method="POST">

                    @csrf

                    <!-- NISN -->
                    <div class="siswa-field">

                        <label
                            for="nisn"
                            class="siswa-label">

                            NISN

                        </label>

                        <div class="siswa-input-wrap">

                            <i class="bi bi-person-vcard siswa-input-icon"></i>

                            <input
                                type="text"
                                id="nisn"
                                name="nisn"
                                class="siswa-input with-icon"
                                value="{{ old('nisn') }}"
                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                autocomplete="username"
                                placeholder="Masukkan 10 digit NISN"
                                required>

                        </div>

                    </div>

                    <!-- PASSWORD -->
                    <div class="siswa-field">

                        <label
                            for="password"
                            class="siswa-label">

                            Password

                        </label>

                        <div class="siswa-input-wrap">

                            <i class="bi bi-lock siswa-input-icon"></i>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="siswa-input with-icon with-toggle"
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                required>

                            <button
                                type="button"
                                id="togglePassword"
                                class="siswa-password-toggle"
                                aria-label="Tampilkan password">

                                <i
                                    id="iconPassword"
                                    class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>

                    <!-- LOGIN -->
                    <button
                        type="submit"
                        class="siswa-button siswa-button-full">

                        <i class="bi bi-box-arrow-in-right"></i>

                        Login Siswa

                    </button>

                </form>

                <!-- INFORMASI -->
                <div class="siswa-info">

                    <i class="bi bi-info-circle"></i>

                    <span>
                        Gunakan akun yang sudah dibuat PJ Lab.
                        Setelah login berhasil, nama,
                        kelas, NISN, dan jam login
                        akan tampil otomatis di Logbook.
                    </span>

                </div>

            </div>

        </div>

    </main>

    <!-- TOGGLE PASSWORD -->
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const button =
                    document.getElementById('togglePassword');

                const password =
                    document.getElementById('password');

                const icon =
                    document.getElementById('iconPassword');

                button.addEventListener('click', function () {

                    const sedangDisembunyikan =
                        password.type === 'password';

                    password.type =
                        sedangDisembunyikan
                            ? 'text'
                            : 'password';

                    icon.className =
                        sedangDisembunyikan
                            ? 'bi bi-eye-slash'
                            : 'bi bi-eye';

                });

            }
        );
    </script>

</body>
</html>
