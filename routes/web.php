<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| SI-LABKOM MTs AL ITTIHADIYAH PEKANBARU
|--------------------------------------------------------------------------
| ROUTING UTAMA APLIKASI - LARAVEL 12
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| 1. LANDING PAGE
|--------------------------------------------------------------------------
| Halaman utama SI-LABKOM.
| Dapat diakses tanpa login.
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| 2. LOGIN PENGELOLA
|--------------------------------------------------------------------------
| Khusus:
| - PJ Lab
| - Guru
| - Kepala Madrasah
|
| Menggunakan email dan password.
*/

Route::middleware('guest')->group(function () {

    // Menampilkan halaman login
    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    // Memproses login
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
| Halaman login siswa dibuat terpisah.
| Nantinya siswa login menggunakan NISN dan password.
*/

Route::get('/siswa/login', function () {
    return view('siswa.auth.login');
})->name('siswa.login');


/*
|--------------------------------------------------------------------------
| 4. LOGBOOK SISWA (SEMENTARA)
|--------------------------------------------------------------------------
| Belum menggunakan CRUD dan database.
| Saat ini hanya halaman placeholder.
| Nantinya wajib login sebagai siswa.
*/

Route::get('/logbook-siswa', function () {
    return 'Halaman Logbook Siswa belum tersedia.';
})->name('logbook.siswa');


/*
|--------------------------------------------------------------------------
| 5. LOGOUT
|--------------------------------------------------------------------------
| Pengguna yang sudah login dapat logout.
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
| Semua route dalam grup ini hanya dapat diakses
| oleh pengguna yang login dengan role pjlab.
*/

Route::middleware(['auth', 'role:pjlab'])
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
        | MENU SIDEBAR PJ LAB
        |--------------------------------------------------------------------------
        | Semua halaman menu sementara menggunakan
        | satu view placeholder.
        |
        | Nantinya setiap fitur memiliki controller,
        | model, dan view masing-masing.
        */

        Route::get('/menu/{menu}', function (string $menu) {

            $menus = [

                // Pengelolaan Laboratorium
                'inventaris' => 'Inventaris Perangkat',

                'operasional' => 'Operasional Laboratorium',

                'monitoring' => 'Monitoring dan Kendala',

                'logbook' => 'Logbook Siswa',

                // Laporan dan Administrasi
                'laporan' => 'Laporan Laboratorium',

                'pengguna' => 'Manajemen Pengguna',

                'pengguna-logbook' =>
                    'Manajemen Pengguna Logbook (Siswa)',

            ];

            // Menolak nama menu yang tidak terdaftar
            abort_unless(
                array_key_exists($menu, $menus),
                404
            );

            // Menampilkan halaman sesuai menu
            return view('pjlab.pages.placeholder', [
                'judul' => $menus[$menu],
                'menuAktif' => $menu,
            ]);

        })->name('menu');

    });


/*
|--------------------------------------------------------------------------
| 7. DASHBOARD GURU
|--------------------------------------------------------------------------
| Khusus pengguna role guru.
*/

Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        Route::get('/dashboard', function () {

            return view('dashboard.index', [
                'roleTitle' => 'Guru',
            ]);

        })->name('dashboard');

    });


/*
|--------------------------------------------------------------------------
| 8. DASHBOARD KEPALA MADRASAH
|--------------------------------------------------------------------------
| Khusus pengguna role kamad.
*/

Route::middleware(['auth', 'role:kamad'])
    ->prefix('kamad')
    ->name('kamad.')
    ->group(function () {

        Route::get('/dashboard', function () {

            return view('dashboard.index', [
                'roleTitle' => 'Kepala Madrasah',
            ]);

        })->name('dashboard');

    });

