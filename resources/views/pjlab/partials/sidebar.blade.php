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

        <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Tutup sidebar">

            <i class="bi bi-x-lg"></i>

        </button>

    </div>


    <!-- NAVIGASI -->
    <nav class="sidebar-nav">

        <!-- MENU UTAMA -->
        <div class="sidebar-section-title">
            MENU UTAMA
        </div>

        <a href="{{ route('pjlab.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('pjlab.dashboard') ? 'active' : '' }}"
            @if (request()->routeIs('pjlab.dashboard')) aria-current="page" @endif>

            <i class="bi bi-grid-1x2"></i>
            <span>Dashboard</span>

        </a>


        <!-- PENGELOLAAN LABORATORIUM -->
        <div class="sidebar-section-title">
            PENGELOLAAN LABORATORIUM
        </div>

        <!-- INVENTARIS -->
        <a href="{{ route('pjlab.menu', ['menu' => 'inventaris']) }}"
            class="sidebar-link {{ request()->route('menu') === 'inventaris' ? 'active' : '' }}">

            <i class="bi bi-hdd-stack"></i>
            <span>Inventaris Perangkat</span>

        </a>


        <!-- OPERASIONAL -->
        <a href="{{ route('pjlab.menu', ['menu' => 'operasional']) }}"
            class="sidebar-link {{ request()->route('menu') === 'operasional' ? 'active' : '' }}">

            <i class="bi bi-calendar-week"></i>
            <span>Operasional Laboratorium</span>

        </a>


        <!-- MONITORING -->
        <a href="{{ route('pjlab.menu', ['menu' => 'monitoring']) }}"
            class="sidebar-link {{ request()->route('menu') === 'monitoring' ? 'active' : '' }}">

            <i class="bi bi-clipboard-check"></i>
            <span>Monitoring dan Kendala</span>

        </a>


        <!-- LOGBOOK -->
        <a href="{{ route('pjlab.menu', ['menu' => 'logbook']) }}"
            class="sidebar-link {{ request()->route('menu') === 'logbook' ? 'active' : '' }}">

            <i class="bi bi-journal-text"></i>
            <span>Logbook Siswa</span>

        </a>


        <!-- ADMINISTRASI -->
        <div class="sidebar-section-title">
            LAPORAN DAN ADMINISTRASI
        </div>

        <!-- LAPORAN -->
        <a href="{{ route('pjlab.menu', ['menu' => 'laporan']) }}"
            class="sidebar-link {{ request()->route('menu') === 'laporan' ? 'active' : '' }}">

            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Laporan Laboratorium</span>

        </a>



        <!-- MANAJEMEN PENGGUNA GURU DAN KAMAD -->
        <a href="{{ route('pjlab.pengguna.index') }}"
            class="sidebar-link {{ request()->routeIs('pjlab.pengguna.*') ? 'active' : '' }}"
            @if (request()->routeIs('pjlab.pengguna.*')) aria-current="page" @endif>

            <i class="bi bi-people"></i>

            <span>Manajemen Pengguna</span>

        </a>




        <!-- ==========================================
     MANAJEMEN PENGGUNA LOGBOOK SISWA
========================================== -->

        @php
            $kelasLogbook = ['7A', '7B', '7C', '7D', '8A', '8B', '8C', '8D', '9A', '9C', '9D'];

            $sedangDiMenuSiswa = request()->routeIs('pjlab.siswa.*');

            $kelasAktif = request()->route('kelas') ?? '7A';
        @endphp

        <details class="pjlab-siswa-menu" @if ($sedangDiMenuSiswa) open @endif>

            <summary
                class="sidebar-link pjlab-siswa-menu-toggle
               {{ $sedangDiMenuSiswa ? 'active' : '' }}">

                <i class="bi bi-people-fill"></i>

                <span>
                    Manajemen Pengguna Logbook (Siswa)
                </span>

                <i class="bi bi-chevron-down pjlab-siswa-chevron"></i>

            </summary>

            <!-- PILIHAN KELAS -->
            <div class="pjlab-siswa-submenu">

                @foreach ($kelasLogbook as $kelasItem)
                    <a href="{{ route('pjlab.siswa.index', ['kelas' => $kelasItem]) }}"
                        class="pjlab-siswa-sublink
                       {{ $sedangDiMenuSiswa && $kelasAktif === $kelasItem ? 'active' : '' }}">

                        <i class="bi bi-mortarboard"></i>

                        <span>
                            Kelas {{ $kelasItem }}
                        </span>

                    </a>
                @endforeach

            </div>

        </details>

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
        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button type="submit" class="sidebar-logout">

                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>

            </button>

        </form>

    </div>

</aside>
