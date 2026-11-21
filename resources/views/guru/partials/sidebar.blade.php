
<aside class="sidebar guru-sidebar" id="sidebar">

    <!-- HEADER / BRAND -->
    <div class="sidebar-brand">

        <div class="sidebar-brand-icon">
            <i class="bi bi-pc-display-horizontal"></i>
        </div>

        <div class="sidebar-brand-text">
            <strong>SI-LABKOM</strong>
            <small>Panel Guru</small>
        </div>

        <button
            type="button"
            class="sidebar-close"
            id="sidebarClose"
            aria-label="Tutup sidebar">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>

    <!-- KONTEN MENU -->
    <div class="guru-sidebar-content">

        <!-- MENU UTAMA -->
        <div class="sidebar-menu-group">

            <div class="sidebar-menu-title">
                MENU UTAMA
            </div>

            <nav class="sidebar-nav" aria-label="Menu Utama">

                <!-- DASHBOARD -->
                <a
                    href="{{ route('guru.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">

                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>

                </a>

            </nav>

        </div>

        <!-- PENGELOLAAN LABORATORIUM -->
        <div class="sidebar-menu-group">

            <div class="sidebar-menu-title">
                PENGELOLAAN LABORATORIUM
            </div>

            <nav
                class="sidebar-nav"
                aria-label="Pengelolaan Laboratorium">

                <!-- OPERASIONAL LABORATORIUM -->
                <a
                    href="{{ route('guru.operasional.index') }}"
                    class="sidebar-link {{ request()->routeIs('guru.operasional.*') ? 'active' : '' }}">

                    <i class="bi bi-calendar-check"></i>

                    <span>Operasional Laboratorium</span>

                </a>

                <!-- RIWAYAT -->
                <a
                    href="{{ route('guru.menu', ['menu' => 'riwayat']) }}"
                    class="sidebar-link {{ request()->routeIs('guru.riwayat') || (request()->routeIs('guru.menu') && request()->route('menu') === 'riwayat') ? 'active' : '' }}">

                    <i class="bi bi-clock-history"></i>

                    <span>Riwayat</span>

                </a>

            </nav>

        </div>

    </div>

    <!-- PROFIL DAN LOGOUT DI BAWAH -->
    <div class="sidebar-bottom">

        <!-- PROFIL GURU -->
        <div class="sidebar-profile">

            <div class="sidebar-profile-avatar">
                <i class="bi bi-person"></i>
            </div>

            <div class="sidebar-profile-info">

                <strong>
                    {{ auth()->user()?->name ?? 'Guru' }}
                </strong>

                <small>Guru</small>

            </div>

        </div>

        <!-- LOGOUT -->
        <form
            action="{{ route('logout') }}"
            method="POST">

            @csrf

            <button
                type="submit"
                class="sidebar-link sidebar-logout">

                <i class="bi bi-box-arrow-right"></i>

                <span>Logout</span>

            </button>

        </form>

    </div>

</aside>
