<aside class="sidebar" id="sidebar">

    <!-- ========================================
         BRAND SIDEBAR
    ======================================== -->
    <div class="sidebar-brand">

        <div class="sidebar-brand-icon">
            <i class="bi bi-pc-display-horizontal"></i>
        </div>

        <div class="sidebar-brand-text">
            <strong>SI-LABKOM</strong>
            <small>Panel Guru</small>
        </div>

        <!-- TOMBOL TUTUP MOBILE -->
        <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup sidebar">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>


    <!-- ========================================
         MENU UTAMA
    ======================================== -->
    <div class="sidebar-menu-title">
        MENU UTAMA
    </div>

    <nav class="sidebar-nav">

        <!-- DASHBOARD -->
        <a href="{{ route('guru.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>

        </a>

    </nav>


    <!-- ========================================
         PENGELOLAAN LABORATORIUM
    ======================================== -->
    <div class="sidebar-menu-title">
        PENGELOLAAN LABORATORIUM
    </div>

    <nav class="sidebar-nav">

        <!-- OPERASIONAL LABORATORIUM -->
        <a href="{{ route('guru.operasional.index') }}"
            class="sidebar-link {{ request()->routeIs('guru.operasional.*') ? 'active' : '' }}">

            <i class="bi bi-calendar-check"></i>
            <span>Operasional Laboratorium</span>

        </a>

        <!-- RIWAYAT -->
        <a href="{{ route('guru.menu', ['menu' => 'riwayat']) }}"
            class="sidebar-link {{ request()->routeIs('guru.menu') && request()->route('menu') === 'riwayat' ? 'active' : '' }}">

            <i class="bi bi-clock-history"></i>
            <span>Riwayat</span>

        </a>

    </nav>


    <!-- ========================================
         BAGIAN BAWAH SIDEBAR
    ======================================== -->
    <div class="sidebar-bottom">

        <!-- PROFIL GURU -->
        <div class="sidebar-bottom">

            <div class="sidebar-profile">
                <div class="sidebar-profile-avatar">
                    <i class="bi bi-person"></i>
                </div>

                <div class="sidebar-profile-info">
                    <strong>{{ auth()->user()?->name ?? 'Guru' }}</strong>
                    <small>Guru</small>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="sidebar-link sidebar-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>

        </div>


        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button type="submit" class="sidebar-link sidebar-logout">

                <i class="bi bi-box-arrow-right"></i>

                <span>Logout</span>

            </button>

        </form>

    </div>

</aside>
