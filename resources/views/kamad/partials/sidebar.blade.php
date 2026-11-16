
<aside class="sidebar" id="sidebar">

    <!-- ==========================================
         IDENTITAS SIDEBAR
    ========================================== -->

    <div class="sidebar-brand">

        <div class="sidebar-brand-icon">
            <i class="bi bi-pc-display-horizontal"></i>
        </div>

        <div class="sidebar-brand-text">

            <strong>SI-LABKOM</strong>

            <small>Panel Kepala Madrasah</small>

        </div>

        <!-- TUTUP SIDEBAR MOBILE -->
        <button
            type="button"
            class="sidebar-close"
            id="sidebarClose"
            aria-label="Tutup sidebar">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>


    <!-- ==========================================
         MENU NAVIGASI
    ========================================== -->

    <nav class="sidebar-nav"
         aria-label="Navigasi Kepala Madrasah">

        <!-- MENU UTAMA -->
        <div class="sidebar-section-title">
            MENU UTAMA
        </div>

        <!-- DASHBOARD -->
        <a
            href="{{ route('kamad.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('kamad.dashboard') ? 'active' : '' }}"
            @if(request()->routeIs('kamad.dashboard'))
                aria-current="page"
            @endif>

            <i class="bi bi-grid-1x2"></i>

            <span>Dashboard</span>

        </a>


        <!-- MONITORING LABORATORIUM -->
        <div class="sidebar-section-title">
            MONITORING LABORATORIUM
        </div>

        <!-- INVENTARIS -->
        <a
            href="{{ route('kamad.menu', ['menu' => 'inventaris']) }}"
            class="sidebar-link {{ request()->routeIs('kamad.menu') && request()->route('menu') === 'inventaris' ? 'active' : '' }}"
            @if(request()->routeIs('kamad.menu') && request()->route('menu') === 'inventaris')
                aria-current="page"
            @endif>

            <i class="bi bi-pc-display"></i>

            <span>Inventaris Perangkat</span>

        </a>


        <!-- PENGGUNAAN LABORATORIUM -->
        <a
            href="{{ route('kamad.menu', ['menu' => 'penggunaan']) }}"
            class="sidebar-link {{ request()->routeIs('kamad.menu') && request()->route('menu') === 'penggunaan' ? 'active' : '' }}"
            @if(request()->routeIs('kamad.menu') && request()->route('menu') === 'penggunaan')
                aria-current="page"
            @endif>

            <i class="bi bi-calendar-check"></i>

            <span>Penggunaan Laboratorium</span>

        </a>


        <!-- MONITORING KENDALA -->
        <a
            href="{{ route('kamad.menu', ['menu' => 'monitoring']) }}"
            class="sidebar-link {{ request()->routeIs('kamad.menu') && request()->route('menu') === 'monitoring' ? 'active' : '' }}"
            @if(request()->routeIs('kamad.menu') && request()->route('menu') === 'monitoring')
                aria-current="page"
            @endif>

            <i class="bi bi-exclamation-triangle"></i>

            <span>Monitoring dan Kendala</span>

        </a>


        <!-- LAPORAN -->
        <div class="sidebar-section-title">
            LAPORAN
        </div>

        <a
            href="{{ route('kamad.menu', ['menu' => 'laporan']) }}"
            class="sidebar-link {{ request()->routeIs('kamad.menu') && request()->route('menu') === 'laporan' ? 'active' : '' }}"
            @if(request()->routeIs('kamad.menu') && request()->route('menu') === 'laporan')
                aria-current="page"
            @endif>

            <i class="bi bi-file-earmark-bar-graph"></i>

            <span>Laporan Laboratorium</span>

        </a>

    </nav>


    <!-- ==========================================
         FOOTER SIDEBAR
    ========================================== -->

    <div class="sidebar-footer">

        <!-- INFORMASI AKUN -->
        <div class="sidebar-user">

            <div class="sidebar-avatar">
                <i class="bi bi-person"></i>
            </div>

            <div class="sidebar-user-info">

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <small>
                    Kepala Madrasah
                </small>

            </div>

        </div>


        <!-- LOGOUT -->
        <form
            action="{{ route('logout') }}"
            method="POST">

            @csrf

            <button
                type="submit"
                class="sidebar-logout">

                <i class="bi bi-box-arrow-right"></i>

                <span>Logout</span>

            </button>

        </form>

    </div>

</aside>
