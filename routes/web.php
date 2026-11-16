
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
| Dapat diakses oleh semua pengunjung.
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| 2. LOGIN PENGELOLA
|--------------------------------------------------------------------------
| PJ Lab, Guru, dan Kepala Madrasah.
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
| Login siswa menggunakan NISN dan password
| akan dibuat dengan controller terpisah.
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
| 5. LOGOUT
|--------------------------------------------------------------------------
| Hanya pengguna yang sudah login.
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
| Hanya bisa diakses oleh akun PJ Lab yang aktif.
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
    | MANAJEMEN PENGGUNA GURU & KEPALA MADRASAH
    |--------------------------------------------------------------------------
    | PJ Lab mempunyai hak untuk:
    | - Melihat daftar akun
    | - Membuat email dan password pengguna
    | - Mengedit data pengguna
    | - Mengganti password pengguna
    | - Mengaktifkan / menonaktifkan akun
    | - Menghapus akun Guru dan Kamad
    |
    | Akun utama PJ Lab dilindungi dari edit/hapus.
    */

    // Menampilkan daftar pengguna
    Route::get('/pengguna', [
        PenggunaController::class,
        'index'
    ])->name('pengguna.index');


    // Menambah akun Guru atau Kamad
    Route::post('/pengguna', [
        PenggunaController::class,
        'store'
    ])->name('pengguna.store');


    // Memperbarui akun, termasuk mengganti password
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
    | MENU SIDEBAR LAINNYA
    |--------------------------------------------------------------------------
    | Masih menggunakan halaman placeholder.
    | Manajemen Pengguna sudah memiliki halaman sendiri.
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

        // Validasi nama menu
        abort_unless(
            array_key_exists($menu, $menus),
            404
        );

        // Halaman sementara masing-masing menu
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
| Hanya untuk akun Guru yang aktif.
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
| Hanya untuk akun Kamad yang aktif.
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

    Route::get('/dashboard', function () {

        return view('dashboard.index', [
            'roleTitle' => 'Kepala Madrasah',
        ]);

    })->name('dashboard');

});
