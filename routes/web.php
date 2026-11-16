
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PjLab\PenggunaController;

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
| Halaman utama yang dapat diakses oleh semua pengunjung.
*/

Route::get('/', function () {

    return view('landing');

})->name('landing');


/*
|--------------------------------------------------------------------------
| 2. LOGIN PENGELOLA
|--------------------------------------------------------------------------
| Digunakan oleh:
| - PJ Lab
| - Guru
| - Kepala Madrasah
|
| Login menggunakan email dan password.
*/

Route::middleware('guest')->group(function () {

    /*
    | Menampilkan halaman login.
    */

    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');


    /*
    | Memproses login pengelola.
    | Rate limit 5 percobaan per menit.
    */

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
| Nantinya siswa login menggunakan NISN dan password.
| Sistem autentikasi siswa akan dibuat secara terpisah.
*/

Route::get('/siswa/login', function () {

    return view('siswa.auth.login');

})->name('siswa.login');


/*
|--------------------------------------------------------------------------
| 4. LOGBOOK SISWA (SEMENTARA)
|--------------------------------------------------------------------------
| Belum menampilkan data pribadi siswa.
*/

Route::get('/logbook-siswa', function () {

    return 'Halaman Logbook Siswa belum tersedia.';

})->name('logbook.siswa');


/*
|--------------------------------------------------------------------------
| 5. LOGOUT PENGELOLA
|--------------------------------------------------------------------------
| Logout hanya dapat dilakukan oleh pengguna yang sudah login.
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
| Hanya dapat diakses oleh akun PJ Lab yang aktif.
|
| PJ Lab dapat:
| - Mengelola inventaris perangkat
| - Mengelola operasional laboratorium
| - Memantau kendala
| - Mengelola logbook siswa
| - Melihat laporan laboratorium
| - Mengelola akun Guru dan Kepala Madrasah
| - Mengelola akun Logbook Siswa (nantinya)
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
    | MANAJEMEN PENGGUNA
    |--------------------------------------------------------------------------
    | PJ Lab mengelola akun Guru dan Kepala Madrasah.
    |
    | Akun utama PJ Lab tidak dapat diedit atau dihapus
    | melalui fitur Manajemen Pengguna.
    */

    // Menampilkan daftar pengguna
    Route::get('/pengguna', [
        PenggunaController::class,
        'index'
    ])->name('pengguna.index');


    // Menambah pengguna Guru atau Kamad
    Route::post('/pengguna', [
        PenggunaController::class,
        'store'
    ])->name('pengguna.store');


    // Memperbarui pengguna, termasuk password
    Route::patch('/pengguna/{user}', [
        PenggunaController::class,
        'update'
    ])->name('pengguna.update');


    // Menghapus akun Guru atau Kamad
    Route::delete('/pengguna/{user}', [
        PenggunaController::class,
        'destroy'
    ])->name('pengguna.destroy');


    /*
    |--------------------------------------------------------------------------
    | MENU PJ LAB LAINNYA
    |--------------------------------------------------------------------------
    | Sementara menggunakan halaman placeholder.
    | Nantinya diganti dengan controller masing-masing.
    */

    Route::get('/menu/{menu}', function (string $menu) {

        $menus = [

            'inventaris' =>
                'Inventaris Perangkat',

            'operasional' =>
                'Operasional Laboratorium',

            'monitoring' =>
                'Monitoring dan Kendala',

            'logbook' =>
                'Logbook Siswa',

            'laporan' =>
                'Laporan Laboratorium',

            'pengguna-logbook' =>
                'Manajemen Pengguna Logbook (Siswa)',

        ];

        /*
        | Pastikan menu yang diminta terdaftar.
        */

        abort_unless(
            array_key_exists($menu, $menus),
            404
        );

        /*
        | Tampilkan halaman sementara.
        */

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
| Hanya bisa diakses oleh akun Guru yang aktif.
|
| Tampilan menggunakan layout:
| resources/views/layouts/guru.blade.php
|
| CSS menggunakan:
| resources/css/pjlab.css
|--------------------------------------------------------------------------
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
    | 1. Jadwal Laboratorium
    | 2. Pengajuan Penggunaan
    | 3. Logbook Kelas
    |
    | Untuk sementara menampilkan halaman placeholder.
    */

    Route::get('/menu/{menu}', function (string $menu) {

        $menus = [

            'jadwal' =>
                'Jadwal Laboratorium',

            'pengajuan' =>
                'Pengajuan Penggunaan',

            'logbook' =>
                'Logbook Kelas',

        ];

        /*
        | Tolak URL menu yang tidak terdaftar.
        */

        abort_unless(
            array_key_exists($menu, $menus),
            404
        );

        /*
        | Tampilkan halaman fitur Guru.
        */

        return view('guru.pages.placeholder', [
            'judul' => $menus[$menu],
            'menuAktif' => $menu,
        ]);

    })->name('menu');

});


/*
|--------------------------------------------------------------------------
| 8. DASHBOARD KEPALA MADRASAH
|--------------------------------------------------------------------------
| Hanya bisa diakses oleh akun Kamad yang aktif.
|
| Untuk saat ini tetap menggunakan halaman sebelumnya.
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

        return view('dashboard.index', [
            'roleTitle' => 'Kepala Madrasah',
        ]);

    })->name('dashboard');

});
