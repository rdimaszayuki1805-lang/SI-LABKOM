
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="SI-LABKOM - Sistem Informasi Pengelolaan Laboratorium Komputer MTs Al Ittihadiyah Pekanbaru.">

    <title>SI-LABKOM | MTs Al Ittihadiyah Pekanbaru</title>

    <!-- CSS UTAMA -->
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
    </style>

    <!-- CSS LANDING -->
    <style>
        {!! file_get_contents(resource_path('css/landing.css')) !!}
    </style>

</head>

<body>

<!-- =====================================================
     HEADER
===================================================== -->

<header class="main-header">

    <div class="landing-container">

        <div class="header-inner">

            <!-- BRAND SEKOLAH -->
            <a href="{{ route('landing') }}"
               class="header-brand">

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

                <div class="header-identity">

                    <div class="system-name">
                        SI-LABKOM MTS AL ITTIHAD
                    </div>

                    <div class="school-name">
                        Sistem Informasi Pengelolaan
                        Laboratorium Komputer
                    </div>

                </div>

            </a>

            <!-- HEADER ACTION -->
            <div class="header-actions">

                <!-- LOGIN SISWA -->
                <a
                    href="{{ route('siswa.login') }}"
                    class="header-btn header-btn-outline">

                    <span class="btn-icon">▣</span>
                    Logbook Siswa

                </a>

                <!-- LOGIN PENGELOLA -->
                <a
                    href="{{ route('login') }}"
                    class="header-btn header-btn-primary">

                    <span class="btn-icon">↪</span>
                    Login

                </a>

            </div>

        </div>

    </div>

</header>


<!-- =====================================================
     HERO SECTION
===================================================== -->

<main class="hero-section">

    <div class="hero-decoration hero-decoration-right"></div>
    <div class="hero-decoration hero-decoration-left"></div>

    <div class="landing-container hero-container">

        <div class="hero-grid">

            <!-- =================================================
                 BAGIAN KIRI
            ================================================= -->

            <section class="hero-left">

                <div class="hero-eyebrow">

                    <span>▣</span>
                    Pengelolaan Laboratorium Komputer

                </div>

                <h1 class="hero-title">
                    Sistem Informasi
                    <br>
                    Pengelolaan
                    <br>
                    Laboratorium Komputer
                </h1>

                <p class="hero-description">
                    SI-LABKOM membantu pengelolaan inventaris perangkat,
                    pemeriksaan kondisi, jadwal penggunaan laboratorium,
                    logbook siswa, pelaporan kendala, pemeliharaan,
                    hingga penyajian laporan dalam satu sistem.
                </p>

                <!-- INFORMASI SEKOLAH -->
                <div class="school-info">

                    <div class="school-info-icon">
                        ▦
                    </div>

                    <span>
                        MTs Al Ittihadiyah Pekanbaru
                    </span>

                </div>

                <!-- FITUR -->
                <div class="feature-list">

                    <div class="feature-chip">
                        <span>▤</span>
                        Inventaris
                    </div>

                    <div class="feature-chip">
                        <span>▦</span>
                        Jadwal Lab
                    </div>

                    <div class="feature-chip">
                        <span>✓</span>
                        Logbook
                    </div>

                    <div class="feature-chip">
                        <span>⚒</span>
                        Pemeliharaan
                    </div>

                    <div class="feature-chip">
                        <span>▥</span>
                        Laporan
                    </div>

                </div>

            </section>


            <!-- =================================================
                 BAGIAN KANAN
            ================================================= -->

            <section class="hero-right">

                <div class="access-wrapper">

                    <div class="access-heading">

                        <h2 class="access-title">
                            Pilih Akses Sistem
                        </h2>

                        <p class="access-subtitle">
                            Silakan pilih layanan sesuai kebutuhan Anda.
                        </p>

                    </div>

                    <div class="access-stack">

                        <!-- =====================================
                             AKSES SISWA
                        ====================================== -->

                        <a
                            href="{{ route('siswa.login') }}"
                            class="access-link">

                            <div class="access-card">

                                <div class="access-icon">
                                    ▤
                                </div>

                                <div class="access-content">

                                    <div class="access-label">
                                        AKSES SISWA
                                    </div>

                                    <h3 class="access-name">
                                        Isi Logbook Siswa
                                    </h3>

                                    <p class="access-description">
                                        Masuk menggunakan NISN dan password
                                        untuk mencatat penggunaan komputer,
                                        kondisi perangkat, serta aktivitas
                                        pembelajaran di laboratorium.
                                    </p>

                                </div>

                                <div class="access-arrow">
                                    →
                                </div>

                            </div>

                        </a>


                        <!-- =====================================
                             AKSES PENGELOLA
                        ====================================== -->

                        <a
                            href="{{ route('login') }}"
                            class="access-link">

                            <div class="access-card">

                                <div class="access-icon">
                                    ◇
                                </div>

                                <div class="access-content">

                                    <div class="access-label">
                                        AKSES PENGGUNA
                                    </div>

                                    <h3 class="access-name">
                                        Login Guru, PJ Lab, dan Kepala Madrasah
                                    </h3>

                                    <p class="access-description">
                                        Masuk menggunakan email dan password
                                        untuk mengakses fitur laboratorium
                                        sesuai hak akses masing-masing pengguna.
                                    </p>

                                </div>

                                <div class="access-arrow">
                                    →
                                </div>

                            </div>

                        </a>

                    </div>


                    <!-- INFORMASI LOGIN -->
                    <div class="access-note">

                        <span class="note-icon">ⓘ</span>

                        <span>
                            Siswa wajib login menggunakan NISN dan password
                            sebelum mengisi logbook. Guru, Penanggung Jawab
                            Laboratorium, dan Kepala Madrasah login menggunakan
                            email dan password yang telah terdaftar.
                        </span>

                    </div>

                </div>

            </section>

        </div>

    </div>

</main>

</body>
</html>
