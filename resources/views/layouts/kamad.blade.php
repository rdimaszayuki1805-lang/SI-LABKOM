
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard Kepala Madrasah')
        | SI-LABKOM
    </title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- CSS SAMA DENGAN PJ LAB -->
    <style>
        {!! file_get_contents(resource_path('css/pjlab.css')) !!}
    </style>

    <!-- PENYESUAIAN KOMPONEN KAMAD -->
    <style>
        /* Menjaga tombol navigasi dapat diklik */
        .kamad-layout .sidebar-link {
            text-decoration: none;
            cursor: pointer;
        }

        .kamad-layout .sidebar-link.active {
            background: #eaf7ef;
            color: #23774f;
        }

        .kamad-layout .sidebar-link.active i {
            color: #23774f;
        }

        /* Topbar: tetap putih dan dua logo */
        .kamad-layout .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .kamad-layout .topbar-left,
        .kamad-layout .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .kamad-layout .topbar-logo-group {
            display: flex;
            align-items: center;
            gap: 7px;
            padding-right: 16px;
            margin-right: 2px;
            border-right: 1px solid #e1e8e4;
            flex-shrink: 0;
        }

        .kamad-layout .topbar-school-logo {
            width: 40px;
            height: 40px;
            display: block;
            object-fit: contain;
        }

        .kamad-layout .topbar-identity {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .kamad-layout .topbar-identity strong {
            color: #17202d;
            font-size: 15px;
        }

        .kamad-layout .topbar-identity small {
            color: #758077;
            font-size: 11px;
        }

        .kamad-layout .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .kamad-layout .topbar-user-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .kamad-layout .topbar-user-info strong {
            font-size: 13px;
            color: #17202d;
        }

        .kamad-layout .topbar-user-info small {
            font-size: 11px;
            color: #748078;
        }

        .kamad-layout .topbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #eaf7ef;
            color: #2f855a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Tombol sidebar responsif */
        .kamad-layout .menu-toggle {
            border: 0;
            background: transparent;
            color: #2f855a;
            font-size: 24px;
            cursor: pointer;
        }

        .kamad-layout .sidebar-close {
            border: 0;
            background: transparent;
            cursor: pointer;
        }

        /* Banner read-only dari HTML Kamad */
        .kamad-readonly-banner {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 15px;
            margin-bottom: 22px;
            background: #f5f9f7;
            border: 1px solid #dbe8e1;
            border-radius: 10px;
            color: #557065;
            font-size: 13px;
            line-height: 1.6;
        }

        .kamad-readonly-banner i {
            color: #2f855a;
            font-size: 17px;
        }

        /* Pelengkap responsif */
        @media (max-width: 768px) {
            .kamad-layout .topbar-logo-group {
                gap: 4px;
                padding-right: 10px;
            }

            .kamad-layout .topbar-school-logo {
                width: 32px;
                height: 32px;
            }

            .kamad-layout .topbar-identity small,
            .kamad-layout .topbar-user-info {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .kamad-layout .topbar-school-logo {
                width: 27px;
                height: 27px;
            }

            .kamad-layout .topbar-logo-group {
                gap: 2px;
                padding-right: 7px;
            }
        }

        /* Sidebar mobile */
        @media (max-width: 991.98px) {
            .kamad-layout .sidebar {
                transition: transform 0.25s ease;
            }

            .kamad-layout .sidebar.show {
                transform: translateX(0);
            }

            .kamad-layout .sidebar-overlay.show {
                display: block;
                opacity: 1;
                pointer-events: auto;
            }
        }
    </style>

    @stack('styles')

</head>

<body class="kamad-layout">

    <div class="app-shell">

        <!-- SIDEBAR -->
        @include('kamad.partials.sidebar')

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

                    <!-- NAMA SISTEM -->
                    <div class="topbar-identity">

                        <strong>SI-LABKOM</strong>

                        <small>
                            MTs Al Ittihadiyah Pekanbaru
                        </small>

                    </div>

                </div>

                <!-- AKUN KAMAD -->
                <div class="topbar-right">

                    <div class="topbar-user">

                        <div class="topbar-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div class="topbar-user-info">

                            <strong>
                                {{ auth()->user()->name }}
                            </strong>

                            <small>
                                Kepala Madrasah
                            </small>

                        </div>

                    </div>

                </div>

            </header>

            <!-- PAGE CONTENT -->
            <main class="page-body">

                <!-- BREADCRUMB -->
                <nav
                    class="breadcrumb-custom"
                    aria-label="Breadcrumb">

                    <a href="{{ route('kamad.dashboard') }}">
                        Beranda
                    </a>

                    <i class="bi bi-chevron-right"></i>

                    <span>
                        @yield('title', 'Dashboard')
                    </span>

                </nav>

                <!-- INFORMASI READ-ONLY -->
                <div class="kamad-readonly-banner"
                     role="status">

                    <i class="bi bi-eye"></i>

                    <span>
                        Anda masuk sebagai
                        <strong>Kepala Madrasah</strong>.
                        Akses Anda hanya untuk melihat
                        informasi laboratorium (read-only).
                    </span>

                </div>

                <!-- KONTEN -->
                @yield('content')

            </main>

        </div>

    </div>

    <!-- JAVASCRIPT SIDEBAR -->
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const sidebar =
                    document.getElementById('sidebar');

                const overlay =
                    document.getElementById('sidebarOverlay');

                const toggle =
                    document.getElementById('sidebarToggle');

                const closeButton =
                    document.getElementById('sidebarClose');

                function setSidebar(open) {

                    if (!sidebar || !overlay) {
                        return;
                    }

                    sidebar.classList.toggle(
                        'show',
                        open
                    );

                    overlay.classList.toggle(
                        'show',
                        open
                    );

                    if (toggle) {
                        toggle.setAttribute(
                            'aria-expanded',
                            String(open)
                        );
                    }
                }

                if (toggle) {

                    toggle.addEventListener(
                        'click',
                        function () {

                            setSidebar(
                                !sidebar.classList.contains('show')
                            );

                        }
                    );
                }

                if (closeButton) {

                    closeButton.addEventListener(
                        'click',
                        function () {
                            setSidebar(false);
                        }
                    );
                }

                if (overlay) {

                    overlay.addEventListener(
                        'click',
                        function () {
                            setSidebar(false);
                        }
                    );
                }

                document.addEventListener(
                    'keydown',
                    function (event) {

                        if (event.key === 'Escape') {
                            setSidebar(false);
                        }
                    }
                );

                // Tutup sidebar saat menu dipilih
                if (sidebar) {

                    sidebar.querySelectorAll(
                        '.sidebar-link'
                    ).forEach(function (link) {

                        link.addEventListener(
                            'click',
                            function () {
                                setSidebar(false);
                            }
                        );

                    });
                }
            }
        );
    </script>

    @stack('scripts')

</body>
</html>
