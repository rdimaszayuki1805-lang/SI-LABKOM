
<?php

use Illuminate\Support\Facades\Route;

// LOGIN PENGELOLA
use App\Http\Controllers\Auth\LoginController;

// FITUR PJ LAB
use App\Http\Controllers\PjLab\PenggunaController;
use App\Http\Controllers\PjLab\SiswaLogbookController;

// LOGIN SISWA
use App\Http\Controllers\Siswa\AuthController as SiswaAuthController;

// LOGBOOK SISWA
use App\Http\Controllers\Siswa\LogbookController as LogbookSiswaController;

// MIDDLEWARE SISWA
use App\Http\Middleware\EnsureSiswaAuthenticated;

/*
|--------------------------------------------------------------------------
| SI-LABKOM MTs AL ITTIHADIYAH PEKANBARU
|--------------------------------------------------------------------------
| ROUTING UTAMA - LARAVEL 12
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| 1. LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| 2. LOGIN PENGELOLA
|--------------------------------------------------------------------------
| Login PJ Lab, Guru, dan Kepala Madrasah.
| Menggunakan email dan password.
*/

Route::middleware('guest')->group(function () {

    // Menampilkan login pengelola
    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    // Memproses login pengelola
    Route::post('/login', [
        LoginController::class,
        'login'
    ])
    ->middleware('throttle:5,1')
    ->name('login.process');

});


/*
|--------------------------------------------------------------------------
| 3. LOGIN SISWA
|--------------------------------------------------------------------------
| Menggunakan NISN dan password dari tabel siswas.
| Tidak menggunakan login pengelola.
*/

// Menampilkan halaman Login Siswa
Route::get('/siswa/login', [
    SiswaAuthController::class,
    'showLogin'
])->name('siswa.login');

// Memproses Login Siswa
Route::post('/siswa/login', [
    SiswaAuthController::class,
    'login'
])
->middleware('throttle:5,1')
->name('siswa.login.process');


/*
|--------------------------------------------------------------------------
| 4. LOGBOOK SISWA
|--------------------------------------------------------------------------
| Hanya siswa yang telah login yang dapat mengakses.
|
| Nama, NISN, kelas, dan waktu login diambil
| otomatis dari database dan session siswa.
*/

Route::middleware([
    EnsureSiswaAuthenticated::class
])
->group(function () {

    // Halaman Logbook Siswa
    Route::get('/logbook-siswa', [
        LogbookSiswaController::class,
        'index'
    ])->name('logbook.siswa');

    // Simpan Logbook Siswa
    Route::post('/logbook-siswa', [
        LogbookSiswaController::class,
        'store'
    ])
    ->middleware('throttle:10,1')
    ->name('logbook.siswa.store');

    // Logout Siswa
    Route::post('/siswa/logout', [
        SiswaAuthController::class,
        'logout'
    ])->name('siswa.logout');

});


/*
|--------------------------------------------------------------------------
| 5. LOGOUT PENGELOLA
|--------------------------------------------------------------------------
| Logout PJ Lab, Guru, dan Kepala Madrasah.
*/

Route::post('/logout', [
    LoginController::class,
    'logout'
])
->middleware('auth')
->name('logout');


/*
|--------------------------------------------------------------------------
| 6. DASHBOARD DAN FITUR PJ LAB
|--------------------------------------------------------------------------
| Hanya akun PJ Lab yang aktif.
*/

Route::middleware([
    'auth',
    'auth.session',
    'active',
    'role:pjlab',
])
->prefix('pjlab')
->name('pjlab.')
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PJ LAB
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('pjlab.dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN PENGGUNA GURU DAN KAMAD
    |--------------------------------------------------------------------------
    */

    // Menampilkan daftar pengguna
    Route::get('/pengguna', [
        PenggunaController::class,
        'index'
    ])->name('pengguna.index');

    // Menambah pengguna
    Route::post('/pengguna', [
        PenggunaController::class,
        'store'
    ])->name('pengguna.store');

    // Mengedit pengguna dan password
    Route::patch('/pengguna/{user}', [
        PenggunaController::class,
        'update'
    ])->name('pengguna.update');

    // Menghapus pengguna
    Route::delete('/pengguna/{user}', [
        PenggunaController::class,
        'destroy'
    ])->name('pengguna.destroy');


    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN PENGGUNA LOGBOOK SISWA
    |--------------------------------------------------------------------------
    | PJ Lab dapat:
    | - Melihat siswa berdasarkan kelas
    | - Menambah siswa manual
    | - Mengimpor siswa melalui Excel
    | - Mengedit nama, NISN, dan password
    | - Menghapus akun siswa
    */

    // Download template Excel
    Route::get('/pengguna-logbook/template', [
        SiswaLogbookController::class,
        'template'
    ])->name('siswa.template');

    // Menampilkan siswa berdasarkan kelas
    Route::get('/pengguna-logbook/{kelas?}', [
        SiswaLogbookController::class,
        'index'
    ])->name('siswa.index');

    // Tambah siswa manual
    Route::post('/pengguna-logbook', [
        SiswaLogbookController::class,
        'store'
    ])->name('siswa.store');

    // Import siswa dari Excel
    Route::post('/pengguna-logbook/import', [
        SiswaLogbookController::class,
        'import'
    ])->name('siswa.import');

    // Edit siswa dan reset password
    Route::patch('/pengguna-logbook/siswa/{siswa}', [
        SiswaLogbookController::class,
        'update'
    ])->name('siswa.update');

    // Hapus siswa
    Route::delete('/pengguna-logbook/siswa/{siswa}', [
        SiswaLogbookController::class,
        'destroy'
    ])->name('siswa.destroy');


    /*
    |--------------------------------------------------------------------------
    | MENU SIDEBAR PJ LAB LAINNYA
    |--------------------------------------------------------------------------
    | Halaman fitur lain masih menggunakan placeholder.
    */

    Route::get('/menu/{menu}', function (string $menu) {

        $menus = [
            'inventaris' => 'Inventaris Perangkat',
            'operasional' => 'Operasional Laboratorium',
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


/*
|--------------------------------------------------------------------------
| 7. DASHBOARD DAN FITUR GURU
|--------------------------------------------------------------------------
| Hanya akun Guru yang aktif.
*/

Route::middleware([
    'auth',
    'auth.session',
    'active',
    'role:guru',
])
->prefix('guru')
->name('guru.')
->group(function () {

    // Dashboard Guru
    Route::get('/dashboard', function () {
        return view('guru.dashboard');
    })->name('dashboard');

    // Menu Guru
    Route::get('/menu/{menu}', function (string $menu) {

        $menus = [
            'jadwal' => 'Jadwal Laboratorium',
            'pengajuan' => 'Pengajuan Penggunaan',
            'logbook' => 'Logbook Kelas',
        ];

        abort_unless(
            array_key_exists($menu, $menus),
            404
        );

        return view('guru.pages.placeholder', [
            'judul' => $menus[$menu],
            'menuAktif' => $menu,
        ]);

    })->name('menu');

});


/*
|--------------------------------------------------------------------------
| 8. DASHBOARD DAN FITUR KEPALA MADRASAH
|--------------------------------------------------------------------------
| Hanya akun Kepala Madrasah yang aktif.
| Akses bersifat read-only.
*/

Route::middleware([
    'auth',
    'auth.session',
    'active',
    'role:kamad',
])
->prefix('kamad')
->name('kamad.')
->group(function () {

    // Dashboard Kepala Madrasah
    Route::get('/dashboard', function () {
        return view('kamad.dashboard');
    })->name('dashboard');

    // Menu Kepala Madrasah
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
