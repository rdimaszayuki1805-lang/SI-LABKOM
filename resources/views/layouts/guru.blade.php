
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard Guru') | SI-LABKOM
    </title>

    <!-- BOOTSTRAP -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- BOOTSTRAP ICONS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS UTAMA: SAMA DENGAN PJ LAB -->
    <style>
        {!! file_get_contents(resource_path('css/pjlab.css')) !!}
    </style>

    @stack('styles')

</head>

<body>

    <div class="app-shell">

        <!-- SIDEBAR GURU -->
        @include('guru.partials.sidebar')

        <!-- OVERLAY MOBILE -->
        <div
            class="sidebar-overlay"
            id="sidebarOverlay">
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">

            <!-- TOPBAR -->
            <header class="topbar" id="topbar">

                <div class="topbar-left">

                    <!-- TOGGLE SIDEBAR -->
                    <button
                        type="button"
                        class="menu-toggle"
                        id="sidebarToggle"
                        aria-label="Buka menu"
                        aria-controls="sidebar"
                        aria-expanded="false">

                        <i class="bi bi-list"></i>

                    </button>

                    <!-- DUA LOGO SEKOLAH -->
                    <div class="topbar-logo-group">

                        <img
                            src="{{ asset('images/logomts.png') }}"
                            alt="Logo MTs Al Ittihadiyah"
                            class="topbar-school-logo">

                        <img
                            src="{{ asset('images/logoykpi.jpg') }}"
                            alt="Logo YKPI"
                            class="topbar-school-logo">

                    </div>

                    <!-- IDENTITAS SISTEM -->
                    <div class="topbar-identity">

                        <strong>SI-LABKOM</strong>

                        <small>
                            MTs Al Ittihadiyah Pekanbaru
                        </small>

                    </div>

                </div>

                <!-- INFORMASI AKUN -->
                <div class="topbar-right">

                    <div class="topbar-user">

                        <div class="topbar-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div class="topbar-user-info">

                            <strong>
                                {{ auth()->user()->name }}
                            </strong>

                            <small>Guru</small>

                        </div>

                    </div>

                </div>

            </header>

            <!-- PAGE BODY -->
            <main class="page-body">

                <!-- BREADCRUMB -->
                <nav
                    class="breadcrumb-custom"
                    aria-label="Breadcrumb">

                    <a href="{{ route('guru.dashboard') }}">
                        Beranda
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    <span>
                        @yield('title', 'Dashboard Guru')
                    </span>

                </nav>

                <!-- KONTEN HALAMAN -->
                @yield('content')

            </main>

        </div>

    </div>

    <!-- JAVASCRIPT SIDEBAR -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const sidebar =
                document.getElementById('sidebar');

            const overlay =
                document.getElementById('sidebarOverlay');

            const toggle =
                document.getElementById('sidebarToggle');

            const closeBtn =
                document.getElementById('sidebarClose');

            function setSidebar(open) {

                if (!sidebar || !overlay || !toggle) {
                    return;
                }

                sidebar.classList.toggle('show', open);

                overlay.classList.toggle('show', open);

                toggle.setAttribute(
                    'aria-expanded',
                    String(open)
                );
            }

            if (toggle) {

                toggle.addEventListener('click', function () {

                    setSidebar(
                        !sidebar.classList.contains('show')
                    );

                });

            }

            if (closeBtn) {

                closeBtn.addEventListener('click', function () {
                    setSidebar(false);
                });

            }

            if (overlay) {

                overlay.addEventListener('click', function () {
                    setSidebar(false);
                });

            }

            document.addEventListener('keydown', function (event) {

                if (event.key === 'Escape') {
                    setSidebar(false);
                }

            });

        });
    </script>

    @stack('scripts')

</body>

</html>
