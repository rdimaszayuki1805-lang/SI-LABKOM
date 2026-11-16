
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


    <!-- ==========================================
         NAVIGASI SIDEBAR
    ========================================== -->

    <nav class="sidebar-nav" aria-label="Navigasi Guru">

        <!-- MENU UTAMA -->
        <div class="sidebar-section-title">
            MENU UTAMA
        </div>

        <!-- DASHBOARD -->
        <a
            href="{{ route('guru.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
            @if(request()->routeIs('guru.dashboard'))
                aria-current="page"
            @endif>

            <i class="bi bi-grid-1x2"></i>

            <span>Dashboard</span>

        </a>


        <!-- PENGELOLAAN LABORATORIUM -->
        <div class="sidebar-section-title">
            PENGELOLAAN LABORATORIUM
        </div>


        <!-- JADWAL LABORATORIUM -->
        <a
            href="{{ route('guru.menu', ['menu' => 'jadwal']) }}"
            class="sidebar-link {{ request()->routeIs('guru.menu') && request()->route('menu') === 'jadwal' ? 'active' : '' }}"
            @if(request()->routeIs('guru.menu') && request()->route('menu') === 'jadwal')
                aria-current="page"
            @endif>

            <i class="bi bi-calendar-week"></i>

            <span>Jadwal Laboratorium</span>

        </a>


        <!-- PENGAJUAN PENGGUNAAN -->
        <a
            href="{{ route('guru.menu', ['menu' => 'pengajuan']) }}"
            class="sidebar-link {{ request()->routeIs('guru.menu') && request()->route('menu') === 'pengajuan' ? 'active' : '' }}"
            @if(request()->routeIs('guru.menu') && request()->route('menu') === 'pengajuan')
                aria-current="page"
            @endif>

            <i class="bi bi-file-earmark-plus"></i>

            <span>Pengajuan Penggunaan</span>

        </a>


        <!-- LOGBOOK KELAS -->
        <a
            href="{{ route('guru.menu', ['menu' => 'logbook']) }}"
            class="sidebar-link {{ request()->routeIs('guru.menu') && request()->route('menu') === 'logbook' ? 'active' : '' }}"
            @if(request()->routeIs('guru.menu') && request()->route('menu') === 'logbook')
                aria-current="page"
            @endif>

            <i class="bi bi-journal-text"></i>

            <span>Logbook Kelas</span>

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

                <small>Guru</small>

            </div>

        </div>


        <!-- LOGOUT -->
        <form
            method="POST"
            action="{{ route('logout') }}">

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
