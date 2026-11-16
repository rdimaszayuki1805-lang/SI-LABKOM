
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PjLab\PenggunaController;
use App\Http\Controllers\PjLab\SiswaLogbookController;

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
| Login untuk PJ Lab, Guru, dan Kepala Madrasah.
| Menggunakan email dan password.
*/

Route::middleware('guest')->group(function () {

    // Halaman login
    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    // Proses login
    Route::post('/login', [
        LoginController::class,
        'login'
    ])
    ->middleware('throttle:5,1')
    ->name('login.process');

});


/*
|--------------------------------------------------------------------------
| 3. LOGIN SISWA (SEMENTARA)
|--------------------------------------------------------------------------
| Login siswa menggunakan NISN dan password
| akan dibuat dengan autentikasi tersendiri.
*/

Route::get('/siswa/login', function () {
    return view('siswa.auth.login');
})->name('siswa.login');


/*
|--------------------------------------------------------------------------
| 4. LOGBOOK SISWA (SEMENTARA)
|--------------------------------------------------------------------------
*/

Route::get('/logbook-siswa', function () {
    return 'Halaman Logbook Siswa belum tersedia.';
})->name('logbook.siswa');


/*
|--------------------------------------------------------------------------
| 5. LOGOUT PENGELOLA
|--------------------------------------------------------------------------
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
| Semua route di dalam grup ini hanya dapat
| diakses oleh akun PJ Lab yang aktif.
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
    | PJ Lab dapat membuat, mengedit, dan menghapus akun
    | Guru serta Kepala Madrasah.
    |
    | Akun utama PJ Lab dilindungi di controller/model.
    */

    // Daftar akun Guru dan Kamad
    Route::get('/pengguna', [
        PenggunaController::class,
        'index'
    ])->name('pengguna.index');

    // Tambah akun Guru dan Kamad
    Route::post('/pengguna', [
        PenggunaController::class,
        'store'
    ])->name('pengguna.store');

    // Edit akun dan reset password
    Route::patch('/pengguna/{user}', [
        PenggunaController::class,
        'update'
    ])->name('pengguna.update');

    // Hapus akun
    Route::delete('/pengguna/{user}', [
        PenggunaController::class,
        'destroy'
    ])->name('pengguna.destroy');


    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN PENGGUNA LOGBOOK (SISWA)
    |--------------------------------------------------------------------------
    | Hanya PJ Lab yang dapat:
    | - Melihat daftar siswa berdasarkan kelas
    | - Menambahkan siswa manual
    | - Mengimpor siswa dari Excel
    | - Mengedit nama, NISN, dan password
    | - Menghapus akun siswa
    |--------------------------------------------------------------------------
    */

    // Download template Excel
    Route::get('/pengguna-logbook/template', [
        SiswaLogbookController::class,
        'template'
    ])->name('siswa.template');


    // Daftar siswa berdasarkan kelas
    Route::get('/pengguna-logbook/{kelas?}', [
        SiswaLogbookController::class,
        'index'
    ])->name('siswa.index');


    // Tambah siswa secara manual
    Route::post('/pengguna-logbook', [
        SiswaLogbookController::class,
        'store'
    ])->name('siswa.store');


    // Import siswa dari Excel
    Route::post('/pengguna-logbook/import', [
        SiswaLogbookController::class,
        'import'
    ])->name('siswa.import');


    // Edit data siswa dan reset password
    Route::patch('/pengguna-logbook/siswa/{siswa}', [
        SiswaLogbookController::class,
        'update'
    ])->name('siswa.update');


    // Hapus akun siswa
    Route::delete('/pengguna-logbook/siswa/{siswa}', [
        SiswaLogbookController::class,
        'destroy'
    ])->name('siswa.destroy');


    /*
    |--------------------------------------------------------------------------
    | MENU SIDEBAR PJ LAB LAINNYA
    |--------------------------------------------------------------------------
    | Menu yang belum dikembangkan masih menggunakan
    | halaman placeholder.
    |
    | Manajemen Pengguna dan Manajemen Siswa
    | sudah mempunyai route tersendiri.
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
| Hanya dapat diakses oleh akun Guru yang aktif.
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

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GURU
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('guru.dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | MENU SIDEBAR GURU
    |--------------------------------------------------------------------------
    */

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
| Kepala Madrasah hanya memiliki akses read-only.
|
| Tidak ada route untuk membuat, mengubah,
| atau menghapus data laboratorium.
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

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD KEPALA MADRASAH
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        return view('kamad.dashboard');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | MENU SIDEBAR KEPALA MADRASAH
    |--------------------------------------------------------------------------
    */

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
