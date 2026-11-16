
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') | SI-LABKOM</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS PJ LAB -->
    @if (file_exists(resource_path('css/pjlab.css')))
        <style>
            {!! file_get_contents(resource_path('css/pjlab.css')) !!}
        </style>
    @endif

    @stack('styles')
</head>

<body>

<div class="app-shell">

    <!-- SIDEBAR -->
    @include('pjlab.partials.sidebar')

    <!-- OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- KONTEN UTAMA -->
    <div class="main-content">

        <!-- TOPBAR -->
        <header class="topbar" id="topbar">

            <div class="topbar-left">

                <button type="button"
                        class="menu-toggle"
                        id="sidebarToggle"
                        aria-label="Buka menu"
                        aria-controls="sidebar"
                        aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>

                <!-- LOGO SEKOLAH -->
                <div class="topbar-logo-group">

                    <img src="{{ asset('images/logomts.png') }}"
                         alt="Logo MTs"
                         class="topbar-school-logo">

                    <img src="{{ asset('images/logoykpi.jpg') }}"
                         alt="Logo YKPI"
                         class="topbar-school-logo">

                </div>

                <div class="topbar-identity">
                    <strong>SI-LABKOM</strong>
                    <small>MTs Al Ittihadiyah Pekanbaru</small>
                </div>

            </div>

            <div class="topbar-right">

                <div class="topbar-user">
                    <div class="topbar-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div class="topbar-user-info">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>Penanggung Jawab Lab</small>
                    </div>
                </div>

            </div>

        </header>

        <!-- ISI HALAMAN -->
        <main class="page-body">

            <nav class="breadcrumb-custom"
                 aria-label="Breadcrumb">

                <a href="{{ route('pjlab.dashboard') }}">
                    Beranda
                </a>

                <i class="bi bi-chevron-right"></i>

                <span>@yield('title', 'Dashboard')</span>

            </nav>

            @yield('content')

        </main>

    </div>

</div>

<!-- SCRIPT SIDEBAR -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function () {
        const opened = sidebar.classList.toggle('show');
        overlay.classList.toggle('show', opened);
        toggle.setAttribute('aria-expanded', String(opened));
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

});
</script>

@stack('scripts')

</body>
</html>
