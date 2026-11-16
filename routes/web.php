
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
| Halaman utama dapat diakses oleh semua pengunjung.
*/

Route::get('/', function () {

    return view('landing');

})->name('landing');


/*
|--------------------------------------------------------------------------
| 2. LOGIN PENGELOLA
|--------------------------------------------------------------------------
| Login untuk:
| - Penanggung Jawab Laboratorium (PJ Lab)
| - Guru
| - Kepala Madrasah (Kamad)
|
| Menggunakan email dan password.
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
    | Maksimal 5 percobaan per menit.
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
| Nantinya login siswa menggunakan NISN dan password.
| Autentikasi siswa akan dibuat terpisah.
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
| Hanya pengguna yang sudah login yang dapat logout.
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
| Hanya akun aktif dengan role PJ Lab yang dapat mengakses.
|
| PJ Lab memiliki akses untuk mengelola:
| - Inventaris perangkat
| - Operasional laboratorium
| - Monitoring dan kendala
| - Logbook siswa
| - Laporan laboratorium
| - Akun Guru dan Kepala Madrasah
| - Akun Logbook Siswa (fitur mendatang)
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
    | Akun utama PJ Lab dilindungi dari edit dan hapus.
    */

    // Menampilkan daftar pengguna
    Route::get('/pengguna', [
        PenggunaController::class,
        'index'
    ])->name('pengguna.index');


    // Menambah akun Guru atau Kepala Madrasah
    Route::post('/pengguna', [
        PenggunaController::class,
        'store'
    ])->name('pengguna.store');


    // Mengedit data pengguna dan mengganti password
    Route::patch('/pengguna/{user}', [
        PenggunaController::class,
        'update'
    ])->name('pengguna.update');


    // Menghapus akun Guru atau Kepala Madrasah
    Route::delete('/pengguna/{user}', [
        PenggunaController::class,
        'destroy'
    ])->name('pengguna.destroy');


    /*
    |--------------------------------------------------------------------------
    | MENU SIDEBAR PJ LAB
    |--------------------------------------------------------------------------
    | Menu yang belum dikembangkan menggunakan placeholder.
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
        | Pastikan nama menu tersedia.
        */

        abort_unless(
            array_key_exists($menu, $menus),
            404
        );

        /*
        | Tampilkan halaman sementara PJ Lab.
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
| Hanya akun aktif dengan role Guru yang dapat mengakses.
|
| Layout:
| resources/views/layouts/guru.blade.php
|
| CSS:
| resources/css/pjlab.css
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
    | Guru dapat melihat jadwal, melakukan pengajuan
    | penggunaan laboratorium, dan memantau logbook kelas.
    |
    | Untuk sementara halaman masih berupa placeholder.
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
        | Tolak nama menu yang tidak terdaftar.
        */

        abort_unless(
            array_key_exists($menu, $menus),
            404
        );

        /*
        | Tampilkan halaman sementara Guru.
        */

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
| Hanya akun aktif dengan role Kamad yang dapat mengakses.
|
| Kepala Madrasah memiliki hak akses READ-ONLY.
|
| Dapat melihat:
| - Dashboard
| - Inventaris Perangkat
| - Penggunaan Laboratorium
| - Monitoring dan Kendala
| - Laporan Laboratorium
|
| Tidak disediakan route tambah, edit, atau hapus data.
|
| Layout:
| resources/views/layouts/kamad.blade.php
|
| CSS:
| resources/css/pjlab.css
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
    | Seluruh halaman bersifat read-only.
    | Fitur masih menggunakan placeholder.
    */

    Route::get('/menu/{menu}', function (string $menu) {

        $menus = [

            'inventaris' =>
                'Inventaris Perangkat',

            'penggunaan' =>
                'Penggunaan Laboratorium',

            'monitoring' =>
                'Monitoring dan Kendala',

            'laporan' =>
                'Laporan Laboratorium',

        ];

        /*
        | Pastikan nama menu terdaftar.
        */

        abort_unless(
            array_key_exists($menu, $menus),
            404
        );

        /*
        | Menampilkan halaman sementara Kamad.
        */

        return view('kamad.pages.placeholder', [
            'judul' => $menus[$menu],
            'menuAktif' => $menu,
        ]);

    })->name('menu');

});
