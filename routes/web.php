
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| SI-LABKOM MTs AL ITTIHADIYAH PEKANBARU
|--------------------------------------------------------------------------
| ROUTING UTAMA APLIKASI
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| 1. LANDING PAGE
|--------------------------------------------------------------------------
| Halaman utama sistem.
| Dapat diakses oleh semua pengunjung.
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| 2. LOGIN PENGELOLA
|--------------------------------------------------------------------------
| Khusus PJ Lab, Guru, dan Kepala Madrasah.
| Menggunakan email dan password.
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        LoginController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        LoginController::class,
        'login'
    ])->middleware('throttle:5,1')
      ->name('login.process');

});


/*
|--------------------------------------------------------------------------
| 3. LOGIN SISWA
|--------------------------------------------------------------------------
| Halaman login siswa terpisah.
| Nantinya menggunakan NISN dan password.
*/

Route::get('/siswa/login', function () {
    return view('siswa.auth.login');
})->name('siswa.login');


/*
|--------------------------------------------------------------------------
| 4. LOGBOOK SISWA
|--------------------------------------------------------------------------
| Sementara halaman placeholder.
| Nanti dilindungi dengan autentikasi siswa.
*/

Route::get('/logbook-siswa', function () {
    return 'Halaman Logbook Siswa';
})->name('logbook.siswa');


/*
|--------------------------------------------------------------------------
| 5. LOGOUT
|--------------------------------------------------------------------------
| Hanya dapat digunakan pengguna yang login.
*/

Route::post('/logout', [
    LoginController::class,
    'logout'
])->middleware('auth')
  ->name('logout');


/*
|--------------------------------------------------------------------------
| 6. DASHBOARD PJ LAB
|--------------------------------------------------------------------------
| Hanya pengguna dengan role pjlab.
*/

Route::middleware(['auth', 'role:pjlab'])
    ->prefix('pjlab')
    ->name('pjlab.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard.index', [
                'roleTitle' => 'PJ Lab'
            ]);
        })->name('dashboard');

    });


/*
|--------------------------------------------------------------------------
| 7. DASHBOARD GURU
|--------------------------------------------------------------------------
| Hanya pengguna dengan role guru.
*/

Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard.index', [
                'roleTitle' => 'Guru'
            ]);
        })->name('dashboard');

    });


/*
|--------------------------------------------------------------------------
| 8. DASHBOARD KEPALA MADRASAH
|--------------------------------------------------------------------------
| Hanya pengguna dengan role kamad.
*/

Route::middleware(['auth', 'role:kamad'])
    ->prefix('kamad')
    ->name('kamad.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('dashboard.index', [
                'roleTitle' => 'Kepala Madrasah'
            ]);
        })->name('dashboard');

    });
