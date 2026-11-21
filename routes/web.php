
<?php

use Illuminate\Support\Facades\Route;

// =====================================================
// CONTROLLER
// =====================================================

// Login pengelola
use App\Http\Controllers\Auth\LoginController;

// PJ Lab
use App\Http\Controllers\PjLab\PenggunaController;
use App\Http\Controllers\PjLab\SiswaLogbookController;
use App\Http\Controllers\PjLab\OperasionalController as PjLabOperasionalController;

// Guru
use App\Http\Controllers\Guru\OperasionalController as GuruOperasionalController;

// Siswa
use App\Http\Controllers\Siswa\AuthController as SiswaAuthController;
use App\Http\Controllers\Siswa\LogbookController as LogbookSiswaController;

// Middleware siswa
use App\Http\Middleware\EnsureSiswaAuthenticated;

/*
|--------------------------------------------------------------------------
| SI-LABKOM MTs AL ITTIHADIYAH PEKANBARU
| ROUTING UTAMA - LARAVEL 12
|--------------------------------------------------------------------------
*/

// =====================================================
// 1. LANDING PAGE
// =====================================================

Route::get('/', function () {
    return view('landing');
})->name('landing');

// =====================================================
// 2. LOGIN PENGELOLA
// =====================================================

// Halaman Login PJ Lab, Guru, Kamad.
// Tidak menggunakan guest agar URL login
// tetap bisa dibuka ketika ada session aktif.
Route::get('/login', [
    LoginController::class,
    'showLogin',
])->name('login');

// Proses login pengelola
Route::post('/login', [
    LoginController::class,
    'login',
])
    ->middleware('throttle:5,1')
    ->name('login.process');

// =====================================================
// 3. LOGIN SISWA
// =====================================================

Route::get('/siswa/login', [
    SiswaAuthController::class,
    'showLogin',
])->name('siswa.login');

Route::post('/siswa/login', [
    SiswaAuthController::class,
    'login',
])
    ->middleware('throttle:5,1')
    ->name('siswa.login.process');

// =====================================================
// 4. LOGBOOK SISWA
// =====================================================

Route::middleware([
    EnsureSiswaAuthenticated::class,
])->group(function () {

    // Halaman Logbook
    Route::get('/logbook-siswa', [
        LogbookSiswaController::class,
        'index',
    ])->name('logbook.siswa');

    // Simpan Logbook
    Route::post('/logbook-siswa', [
        LogbookSiswaController::class,
        'store',
    ])
        ->middleware('throttle:10,1')
        ->name('logbook.siswa.store');

    // Logout Siswa
    Route::post('/siswa/logout', [
        SiswaAuthController::class,
        'logout',
    ])->name('siswa.logout');
});

// =====================================================
// 5. LOGOUT PENGELOLA
// =====================================================

Route::post('/logout', [
    LoginController::class,
    'logout',
])
    ->middleware('auth')
    ->name('logout');

// =====================================================
// 6. PJ LAB
// =====================================================

Route::middleware([
    'auth',
    'auth.session',
    'active',
    'role:pjlab',
])
    ->prefix('pjlab')
    ->name('pjlab.')
    ->group(function () {

        // ---------------------------------------------
        // DASHBOARD
        // ---------------------------------------------

        Route::get('/dashboard', function () {
            return view('pjlab.dashboard');
        })->name('dashboard');

        // ---------------------------------------------
        // MANAJEMEN PENGGUNA
        // ---------------------------------------------

        Route::get('/pengguna', [
            PenggunaController::class,
            'index',
        ])->name('pengguna.index');

        Route::post('/pengguna', [
            PenggunaController::class,
            'store',
        ])->name('pengguna.store');

        Route::patch('/pengguna/{user}', [
            PenggunaController::class,
            'update',
        ])->name('pengguna.update');

        Route::delete('/pengguna/{user}', [
            PenggunaController::class,
            'destroy',
        ])->name('pengguna.destroy');

        // ---------------------------------------------
        // MANAJEMEN PENGGUNA LOGBOOK SISWA
        // ---------------------------------------------

        Route::get('/pengguna-logbook/template', [
            SiswaLogbookController::class,
            'template',
        ])->name('siswa.template');

        Route::get('/pengguna-logbook/{kelas?}', [
            SiswaLogbookController::class,
            'index',
        ])->name('siswa.index');

        Route::post('/pengguna-logbook', [
            SiswaLogbookController::class,
            'store',
        ])->name('siswa.store');

        Route::post('/pengguna-logbook/import', [
            SiswaLogbookController::class,
            'import',
        ])->name('siswa.import');

        Route::patch('/pengguna-logbook/siswa/{siswa}', [
            SiswaLogbookController::class,
            'update',
        ])->name('siswa.update');

        Route::delete('/pengguna-logbook/siswa/{siswa}', [
            SiswaLogbookController::class,
            'destroy',
        ])->name('siswa.destroy');

        // ---------------------------------------------
        // OPERASIONAL LABORATORIUM PJ LAB
        // ---------------------------------------------

        Route::get('/operasional', [
            PjLabOperasionalController::class,
            'index',
        ])->name('operasional.index');

        // Setujui atau tolak pengajuan Guru
        Route::patch('/operasional/pengajuan/{peminjaman}', [
            PjLabOperasionalController::class,
            'keputusan',
        ])->name('operasional.keputusan');

        // Tambah Jadwal
        Route::post('/operasional/jadwal', [
            PjLabOperasionalController::class,
            'simpanJadwal',
        ])->name('operasional.jadwal.store');

        // Checklist Kesiapan
        Route::post('/operasional/checklist', [
            PjLabOperasionalController::class,
            'simpanChecklist',
        ])->name('operasional.checklist.store');

        // ---------------------------------------------
        // REDIRECT MENU LAMA
        // ---------------------------------------------

        Route::get('/menu/operasional', function () {
            return redirect()->route(
                'pjlab.operasional.index'
            );
        });

        // ---------------------------------------------
        // MENU PJ LAB LAINNYA
        // ---------------------------------------------

        Route::get('/menu/{menu}', function (string $menu) {

            $menus = [
                'inventaris' => 'Inventaris Perangkat',
                'monitoring' => 'Monitoring dan Kendala',
                'logbook' => 'Logbook Siswa',
                'laporan' => 'Laporan Laboratorium',
            ];

            abort_unless(
                array_key_exists($menu, $menus),
                404
            );

            return view('pjlab.pages.placeholder', [
                'judul' => $menus[$menu],
                'menuAktif' => $menu,
            ]);

        })->name('menu');
    });

// =====================================================
// 7. GURU
// =====================================================

Route::middleware([
    'auth',
    'auth.session',
    'active',
    'role:guru',
])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        // ---------------------------------------------
        // DASHBOARD
        // ---------------------------------------------

        Route::get('/dashboard', function () {
            return view('guru.dashboard');
        })->name('dashboard');

        // ---------------------------------------------
        // OPERASIONAL GURU
        // ---------------------------------------------
        // Tab:
        // Pengajuan Saya
        // Pengajuan Peminjaman
        // Jadwal Mata Pelajaran (hanya melihat)
        // Logbook Kelas (hanya melihat)

        Route::get('/operasional', [
            GuruOperasionalController::class,
            'index',
        ])->name('operasional.index');

        // Mengirim pengajuan ke PJ Lab
        Route::post('/operasional/pengajuan', [
            GuruOperasionalController::class,
            'store',
        ])
            ->middleware('throttle:5,1')
            ->name('operasional.pengajuan.store');

        // ---------------------------------------------
        // REDIRECT MENU GURU LAMA
        // ---------------------------------------------

        Route::get('/menu/operasional', function () {
            return redirect()->route(
                'guru.operasional.index'
            );
        });

        Route::get('/menu/pengajuan', function () {
            return redirect()->route(
                'guru.operasional.index',
                ['tab' => 'ajukan']
            );
        });

        Route::get('/menu/jadwal', function () {
            return redirect()->route(
                'guru.operasional.index',
                ['tab' => 'jadwal']
            );
        });

        Route::get('/menu/logbook', function () {
            return redirect()->route(
                'guru.operasional.index',
                ['tab' => 'logbook']
            );
        });

        // ---------------------------------------------
        // RIWAYAT GURU
        // ---------------------------------------------

        Route::get('/menu/riwayat', function () {

            return view('guru.pages.placeholder', [
                'judul' => 'Riwayat Penggunaan Laboratorium',
                'menuAktif' => 'riwayat',
            ]);

        })->name('riwayat');

        // ---------------------------------------------
        // KOMPATIBILITAS ROUTE MENU GURU
        // ---------------------------------------------

        Route::get('/menu/{menu}', function (string $menu) {

            abort_unless(
                $menu === 'riwayat',
                404
            );

            return redirect()->route('guru.riwayat');

        })->name('menu');
    });

// =====================================================
// 8. KEPALA MADRASAH
// =====================================================

Route::middleware([
    'auth',
    'auth.session',
    'active',
    'role:kamad',
])
    ->prefix('kamad')
    ->name('kamad.')
    ->group(function () {

        // Dashboard Kamad
        Route::get('/dashboard', function () {
            return view('kamad.dashboard');
        })->name('dashboard');

        // Menu Kamad
        Route::get('/menu/{menu}', function (string $menu) {

            $menus = [
                'inventaris' => 'Inventaris Perangkat',
                'penggunaan' => 'Penggunaan Laboratorium',
                'monitoring' => 'Monitoring dan Kendala',
                'laporan' => 'Laporan Laboratorium',
            ];

            abort_unless(
                array_key_exists($menu, $menus),
                404
            );

            return view('kamad.pages.placeholder', [
                'judul' => $menus[$menu],
                'menuAktif' => $menu,
            ]);

        })->name('menu');
    });
