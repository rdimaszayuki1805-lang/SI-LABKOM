
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login | SI-LABKOM MTS AL ITTIHAD</title>

    <!-- BOOTSTRAP -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS UTAMA -->
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
    </style>

    <!-- CSS LOGIN -->
    <style>
        {!! file_get_contents(resource_path('css/login.css')) !!}
    </style>

</head>

<body>

    <!-- =====================================================
         HEADER
    ===================================================== -->

    <header class="main-header">

        <div class="container header-container">

            <div class="header-left">

                <!-- LOGO -->
                <div class="header-logos">

                    <img
                        src="{{ asset('images/logomts.png') }}"
                        alt="Logo MTs Al Ittihadiyah"
                        class="header-logo">

                    <img
                        src="{{ asset('images/logoykpi.jpg') }}"
                        alt="Logo YKPI"
                        class="header-logo">

                </div>

                <!-- IDENTITAS -->
                <div class="header-text">

                    <div class="header-title">
                        SI-LABKOM MTS AL ITTIHAD
                    </div>

                    <div class="header-subtitle">
                        Sistem Informasi Pengelolaan Laboratorium Komputer
                    </div>

                </div>

            </div>


            <!-- HEADER ACTIONS -->
            <div class="header-actions">

                <!-- LOGIN SISWA -->
                <a
                    href="{{ route('siswa.login') }}"
                    class="header-btn header-btn-logbook"
                    title="Login Siswa">

                    <i class="bi bi-journal-text"></i>
                    <span>Isi Logbook Siswa</span>

                </a>

                <!-- BERANDA -->
                <a
                    href="{{ route('landing') }}"
                    class="header-btn header-btn-home">

                    <i class="bi bi-arrow-left"></i>
                    <span>Beranda</span>

                </a>

            </div>

        </div>

    </header>


    <!-- =====================================================
         LOGIN PAGE
    ===================================================== -->

    <main class="login-page">

        <div class="login-wrapper">

            <!-- LOGIN HEADING -->
            <div class="login-heading">

                <div class="login-heading-icon">
                    <i class="bi bi-person-lock"></i>
                </div>

                <h1 class="login-title">
                    Masuk ke Sistem
                </h1>

                <p class="login-subtitle">
                    Silakan masuk menggunakan akun yang telah terdaftar
                </p>

            </div>


            <!-- LOGIN CARD -->
            <div class="login-card">

                <!-- FORM LOGIN -->
                <form
                    id="loginForm"
                    method="POST"
                    action="{{ route('login.process') }}">

                    @csrf

                    <!-- EMAIL -->
                    <div class="mb-3">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukkan email"
                            autocomplete="email"
                            maxlength="255"
                            required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- PASSWORD -->
                    <div>

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required>

                            <button
                                type="button"
                                id="togglePass"
                                class="toggle-password"
                                aria-label="Tampilkan password">

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                        @error('password')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- OPTIONS -->
                    <div class="login-options">

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                id="rememberMe"
                                class="form-check-input"
                                {{ old('remember') ? 'checked' : '' }}>

                            <label
                                for="rememberMe"
                                class="form-check-label">
                                Ingat saya
                            </label>

                        </div>

                        <!-- BELUM AKTIF -->
                        <span class="forgot-link">
                            Lupa password?
                        </span>

                    </div>


                    <!-- BUTTON LOGIN -->
                    <button type="submit" class="btn-login">

                        Masuk
                        <i class="bi bi-arrow-right"></i>

                    </button>

                </form>


                <!-- DIVIDER -->
                <div class="divider"></div>


                <!-- INFORMASI PENGGUNA -->
                <div class="demo-header">

                    <p class="demo-title">
                        Jenis Pengguna
                    </p>

                    <span class="demo-help">
                        Akun dikelola PJ Lab
                    </span>

                </div>


                <!-- ROLE GRID -->
                <div class="demo-grid">

                    <!-- PJ LAB -->
                    <div class="demo-account">

                        <div class="demo-top">

                            <div class="demo-role">
                                Penanggung Jawab Laboratorium
                            </div>

                            <div class="demo-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>

                        </div>

                        <div class="demo-email">
                            Administrator Laboratorium
                        </div>

                    </div>


                    <!-- GURU -->
                    <div class="demo-account">

                        <div class="demo-top">

                            <div class="demo-role">
                                Guru
                            </div>

                            <div class="demo-icon">
                                <i class="bi bi-person-workspace"></i>
                            </div>

                        </div>

                        <div class="demo-email">
                            Pengguna Laboratorium
                        </div>

                    </div>


                    <!-- KEPALA MADRASAH -->
                    <div class="demo-account">

                        <div class="demo-top">

                            <div class="demo-role">
                                Kepala Madrasah
                            </div>

                            <div class="demo-icon">
                                <i class="bi bi-building"></i>
                            </div>

                        </div>

                        <div class="demo-email">
                            Monitoring Laboratorium
                        </div>

                    </div>

                </div>


                <!-- FOOTER -->
                <div class="login-footer">
                    MTs Al Ittihadiyah Pekanbaru
                </div>

            </div>

        </div>

    </main>


    <!-- =====================================================
         JAVASCRIPT
    ===================================================== -->

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SWEETALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const togglePass = document.getElementById('togglePass');
            const passwordInput = document.getElementById('password');
            const loginForm = document.getElementById('loginForm');

            /*
            |--------------------------------------------------------------------------
            | SHOW / HIDE PASSWORD
            |--------------------------------------------------------------------------
            */

            togglePass.addEventListener('click', function () {

                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {

                    passwordInput.type = 'text';

                    icon.classList.replace(
                        'bi-eye',
                        'bi-eye-slash'
                    );

                    this.setAttribute(
                        'aria-label',
                        'Sembunyikan password'
                    );

                } else {

                    passwordInput.type = 'password';

                    icon.classList.replace(
                        'bi-eye-slash',
                        'bi-eye'
                    );

                    this.setAttribute(
                        'aria-label',
                        'Tampilkan password'
                    );

                }

            });


            /*
            |--------------------------------------------------------------------------
            | LOADING LOGIN
            |--------------------------------------------------------------------------
            */

            loginForm.addEventListener('submit', function () {

                if (typeof Swal !== 'undefined') {

                    Swal.fire({
                        title: 'Memverifikasi akun...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading()
                    });

                }

            });

        });
    </script>


    <!-- ERROR LOGIN -->
    @if ($errors->any())

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const message = @json($errors->first());

                if (typeof Swal !== 'undefined') {

                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: message,
                        confirmButtonColor: '#2F855A'
                    });

                }

            });
        </script>

    @endif

</body>
</html>
