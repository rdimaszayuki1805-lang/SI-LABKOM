
<!DOCTYPE html>
<html lang="id">


<!-- BOOTSTRAP CSS -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<!-- BOOTSTRAP ICONS -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- CSS DASAR SI-LABKOM -->
<style>
    {!! file_get_contents(resource_path('css/pjlab.css')) !!}
</style>

<!-- CSS KHUSUS GURU, DIMUAT SETELAH CSS PJ LAB -->
<style>
    {!! file_get_contents(resource_path('css/guru.css')) !!}
</style>

@stack('styles')

<body>

    <div class="app-shell">

        <!-- SIDEBAR GURU -->
        @include('guru.partials.sidebar')

        <!-- OVERLAY MOBILE -->
        <div
            class="sidebar-overlay"
            id="sidebarOverlay"
            aria-hidden="true">
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">

            <!-- TOPBAR -->
            <header class="topbar" id="topbar">

                <div class="topbar-left">

                    <!-- SIDEBAR TOGGLE -->
                    <button
                        type="button"
                        class="menu-toggle"
                        id="sidebarToggle"
                        aria-label="Buka menu navigasi"
                        aria-controls="sidebar"
                        aria-expanded="false">

                        <i class="bi bi-list"></i>
                    </button>

                    <!-- DUA LOGO -->
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
                        <small>MTs Al Ittihadiyah Pekanbaru</small>
                    </div>

                </div>

                <!-- AKUN GURU -->
                <div class="topbar-right">

                    <div class="topbar-user">

                        <div class="topbar-avatar">
                            <i class="bi bi-person-fill"></i>
                        </div>

                        <div class="topbar-user-info">
                            <strong>
                                {{ auth()->user()?->name ?? 'Guru' }}
                            </strong>
                            <small>Guru</small>
                        </div>

                    </div>

                </div>

            </header>

            <!-- PAGE BODY -->
            <main class="page-body" id="mainContent">

                <!-- BREADCRUMB -->
                <nav
                    class="breadcrumb-custom"
                    aria-label="Breadcrumb">

                    <a href="{{ route('guru.dashboard') }}">
                        Beranda
                    </a>

                    @unless(request()->routeIs('guru.dashboard'))
                        <i class="bi bi-chevron-right"></i>

                        <span>
                            @yield('title', 'Dashboard Guru')
                        </span>
                    @endunless

                </nav>

                <!-- KONTEN -->
                @yield('content')

            </main>

        </div>

    </div>

    <!-- BOOTSTRAP JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <!-- JAVASCRIPT SIDEBAR -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggle = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');

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

                toggle.setAttribute(
                    'aria-label',
                    open ? 'Tutup menu' : 'Buka menu'
                );

                overlay.setAttribute(
                    'aria-hidden',
                    String(!open)
                );
            }

            if (toggle && sidebar) {
                toggle.addEventListener('click', function () {
                    setSidebar(!sidebar.classList.contains('show'));
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

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 992) {
                    setSidebar(false);
                }
            });

            // Tutup sidebar setelah memilih menu pada mobile.
            if (sidebar) {
                sidebar.querySelectorAll('a[href]').forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (window.innerWidth < 992) {
                            setSidebar(false);
                        }
                    });
                });
            }

        });
    </script>

    @stack('scripts')

</body>
</html>
