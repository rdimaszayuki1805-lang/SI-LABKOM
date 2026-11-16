
<aside class="sidebar" id="sidebar">

    <!-- SIDEBAR HEADER -->
    <div class="sidebar-brand">

        <div class="sidebar-brand-icon">
            <i class="bi bi-pc-display-horizontal"></i>
        </div>

        <div class="sidebar-brand-text">

            <strong>SI-LABKOM</strong>

            <small>
                Panel PJ Laboratorium
            </small>

        </div>

        <button
            type="button"
            class="sidebar-close"
            id="sidebarClose"
            aria-label="Tutup sidebar">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>


    <!-- NAVIGASI -->
    <nav class="sidebar-nav">

        <!-- MENU UTAMA -->
        <div class="sidebar-section-title">
            MENU UTAMA
        </div>

        <a
            href="{{ route('pjlab.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('pjlab.dashboard') ? 'active' : '' }}"
            @if(request()->routeIs('pjlab.dashboard'))
                aria-current="page"
            @endif>

            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>

        </a>


        <!-- PENGELOLAAN LABORATORIUM -->
        <div class="sidebar-section-title">
            PENGELOLAAN LABORATORIUM
        </div>

        <!-- INVENTARIS -->
        <a
            href="{{ route('pjlab.menu', ['menu' => 'inventaris']) }}"
            class="sidebar-link {{ request()->route('menu') === 'inventaris' ? 'active' : '' }}">

            <i class="bi bi-hdd-stack"></i>
            <span>Inventaris Perangkat</span>

        </a>


        <!-- OPERASIONAL -->
        <a
            href="{{ route('pjlab.menu', ['menu' => 'operasional']) }}"
            class="sidebar-link {{ request()->route('menu') === 'operasional' ? 'active' : '' }}">

            <i class="bi bi-calendar-week"></i>
            <span>Operasional Laboratorium</span>

        </a>


        <!-- MONITORING -->
        <a
            href="{{ route('pjlab.menu', ['menu' => 'monitoring']) }}"
            class="sidebar-link {{ request()->route('menu') === 'monitoring' ? 'active' : '' }}">

            <i class="bi bi-clipboard-check"></i>
            <span>Monitoring dan Kendala</span>

        </a>


        <!-- LOGBOOK -->
        <a
            href="{{ route('pjlab.menu', ['menu' => 'logbook']) }}"
            class="sidebar-link {{ request()->route('menu') === 'logbook' ? 'active' : '' }}">

            <i class="bi bi-journal-text"></i>
            <span>Logbook Siswa</span>

        </a>


        <!-- ADMINISTRASI -->
        <div class="sidebar-section-title">
            LAPORAN DAN ADMINISTRASI
        </div>

        <!-- LAPORAN -->
        <a
            href="{{ route('pjlab.menu', ['menu' => 'laporan']) }}"
            class="sidebar-link {{ request()->route('menu') === 'laporan' ? 'active' : '' }}">

            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Laporan Laboratorium</span>

        </a>


        <!-- MANAJEMEN PENGGUNA -->
        <a
            href="{{ route('pjlab.menu', ['menu' => 'pengguna']) }}"
            class="sidebar-link {{ request()->route('menu') === 'pengguna' ? 'active' : '' }}">

            <i class="bi bi-people"></i>
            <span>Manajemen Pengguna</span>

        </a>


        <!-- MANAJEMEN PENGGUNA LOGBOOK -->
        <a
            href="{{ route('pjlab.menu', ['menu' => 'pengguna-logbook']) }}"
            class="sidebar-link {{ request()->route('menu') === 'pengguna-logbook' ? 'active' : '' }}">

            <i class="bi bi-mortarboard"></i>

            <span>
                Manajemen Pengguna Logbook (Siswa)
            </span>

        </a>

    </nav>


    <!-- SIDEBAR FOOTER -->
    <div class="sidebar-footer">

        <!-- INFORMASI USER -->
        <div class="sidebar-user">

            <div class="sidebar-avatar">
                <i class="bi bi-person"></i>
            </div>

            <div class="sidebar-user-info">

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <small>
                    Penanggung Jawab Lab
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
