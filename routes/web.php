
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLER LOGIN PENGELOLA
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| CONTROLLER PJ LAB
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\PjLab\PenggunaController;
use App\Http\Controllers\PjLab\SiswaLogbookController;
use App\Http\Controllers\PjLab\OperasionalController;

/*
|--------------------------------------------------------------------------
| CONTROLLER SISWA
|--------------------------------------------------------------------------
| Menggunakan alias agar tidak terjadi
| bentrok nama controller.
*/

use App\Http\Controllers\Siswa\AuthController as SiswaAuthController;
use App\Http\Controllers\Siswa\LogbookController as LogbookSiswaController;

/*
|--------------------------------------------------------------------------
| MIDDLEWARE SISWA
|--------------------------------------------------------------------------
*/

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
| Halaman utama dapat diakses semua pengunjung.
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
| 3. LOGIN SISWA
|--------------------------------------------------------------------------
| Menggunakan NISN dan password
| dari tabel siswas.
*/

Route::get('/siswa/login', [
    SiswaAuthController::class,
    'showLogin'
])->name('siswa.login');


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
| Hanya siswa yang memiliki sesi login valid
| yang dapat mengakses halaman Logbook.
*/

Route::middleware([
    EnsureSiswaAuthenticated::class,
])
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | HALAMAN LOGBOOK
    |--------------------------------------------------------------------------
    */

    Route::get('/logbook-siswa', [
        LogbookSiswaController::class,
        'index'
    ])->name('logbook.siswa');


    /*
    |--------------------------------------------------------------------------
    | SIMPAN LOGBOOK
    |--------------------------------------------------------------------------
    */

    Route::post('/logbook-siswa', [
        LogbookSiswaController::class,
        'store'
    ])
    ->middleware('throttle:10,1')
    ->name('logbook.siswa.store');


    /*
    |--------------------------------------------------------------------------
    | LOGOUT SISWA
    |--------------------------------------------------------------------------
    */

    Route::post('/siswa/logout', [
        SiswaAuthController::class,
        'logout'
    ])->name('siswa.logout');

});


/*
|--------------------------------------------------------------------------
| 5. LOGOUT PENGELOLA
|--------------------------------------------------------------------------
| Untuk PJ Lab, Guru, dan Kepala Madrasah.
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
| Semua route hanya dapat diakses
| oleh akun aktif dengan role pjlab.
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
    | A. DASHBOARD PJ LAB
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        return view('pjlab.dashboard');

    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | B. MANAJEMEN PENGGUNA GURU DAN KAMAD
    |--------------------------------------------------------------------------
    */

    // Menampilkan daftar pengguna.
    Route::get('/pengguna', [
        PenggunaController::class,
        'index'
    ])->name('pengguna.index');


    // Menambahkan pengguna.
    Route::post('/pengguna', [
        PenggunaController::class,
        'store'
    ])->name('pengguna.store');


    // Mengedit pengguna dan password.
    Route::patch('/pengguna/{user}', [
        PenggunaController::class,
        'update'
    ])->name('pengguna.update');


    // Menghapus pengguna.
    Route::delete('/pengguna/{user}', [
        PenggunaController::class,
        'destroy'
    ])->name('pengguna.destroy');


    /*
    |--------------------------------------------------------------------------
    | C. MANAJEMEN PENGGUNA LOGBOOK SISWA
    |--------------------------------------------------------------------------
    | PJ Lab dapat:
    | - Menampilkan siswa berdasarkan kelas
    | - Menambahkan akun siswa
    | - Mengimpor siswa melalui Excel
    | - Mengedit nama, NISN, dan password
    | - Menghapus akun siswa
    */

    // Download template Excel.
    Route::get('/pengguna-logbook/template', [
        SiswaLogbookController::class,
        'template'
    ])->name('siswa.template');


    // Menampilkan daftar siswa per kelas.
    Route::get('/pengguna-logbook/{kelas?}', [
        SiswaLogbookController::class,
        'index'
    ])->name('siswa.index');


    // Menambahkan siswa manual.
    Route::post('/pengguna-logbook', [
        SiswaLogbookController::class,
        'store'
    ])->name('siswa.store');


    // Import siswa melalui Excel.
    Route::post('/pengguna-logbook/import', [
        SiswaLogbookController::class,
        'import'
    ])->name('siswa.import');


    // Mengedit data siswa.
    Route::patch('/pengguna-logbook/siswa/{siswa}', [
        SiswaLogbookController::class,
        'update'
    ])->name('siswa.update');


    // Menghapus akun siswa.
    Route::delete('/pengguna-logbook/siswa/{siswa}', [
        SiswaLogbookController::class,
        'destroy'
    ])->name('siswa.destroy');


    /*
    |--------------------------------------------------------------------------
    | D. OPERASIONAL LABORATORIUM
    |--------------------------------------------------------------------------
    | Memiliki empat fitur utama:
    |
    | 1. Pengajuan Peminjaman Lab-Kom
    | 2. Jadwal Mata Pelajaran Lab-Kom
    | 3. Checklist Kesiapan
    | 4. Monitoring Logbook
    |
    | Monitoring Logbook hanya menggunakan
    | filter Tanggal dan Kelas.
    */

    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN HALAMAN OPERASIONAL
    |--------------------------------------------------------------------------
    */

    Route::get('/operasional', [
        OperasionalController::class,
        'index'
    ])->name('operasional.index');


    /*
    |--------------------------------------------------------------------------
    | MENYETUJUI ATAU MENOLAK PENGAJUAN
    |--------------------------------------------------------------------------
    */

    Route::patch('/operasional/pengajuan/{peminjaman}', [
        OperasionalController::class,
        'keputusan'
    ])->name('operasional.keputusan');


    /*
    |--------------------------------------------------------------------------
    | MENAMBAHKAN JADWAL MATA PELAJARAN
    |--------------------------------------------------------------------------
    */

    Route::post('/operasional/jadwal', [
        OperasionalController::class,
        'simpanJadwal'
    ])->name('operasional.jadwal.store');


    /*
    |--------------------------------------------------------------------------
    | MENYIMPAN CHECKLIST KESIAPAN
    |--------------------------------------------------------------------------
    */

    Route::post('/operasional/checklist', [
        OperasionalController::class,
        'simpanChecklist'
    ])->name('operasional.checklist.store');


    /*
    |--------------------------------------------------------------------------
    | KOMPATIBILITAS MENU OPERASIONAL LAMA
    |--------------------------------------------------------------------------
    | Jika terdapat tautan lama menuju
    | /pjlab/menu/operasional,
    | langsung arahkan ke halaman baru.
    */

    Route::get('/menu/operasional', function () {

        return redirect()->route(
            'pjlab.operasional.index'
        );

    });


    /*
    |--------------------------------------------------------------------------
    | E. MENU PJ LAB LAINNYA
    |--------------------------------------------------------------------------
    | Menu yang belum dikembangkan tetap
    | menggunakan halaman placeholder.
    */

    Route::get('/menu/{menu}', function (string $menu) {

        $menus = [

            'inventaris' =>
                'Inventaris Perangkat',

            'monitoring' =>
                'Monitoring dan Kendala',

            'logbook' =>
                'Logbook Siswa',

            'laporan' =>
                'Laporan Laboratorium',

        ];

        /*
        | Tolak menu yang tidak terdaftar.
        */

        abort_unless(
            array_key_exists($menu, $menus),
            404
        );

        /*
        | Tampilkan halaman placeholder.
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
| Hanya akun aktif dengan role guru.
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

            'jadwal' =>
                'Jadwal Laboratorium',

            'pengajuan' =>
                'Pengajuan Penggunaan',

            'logbook' =>
                'Logbook Kelas',

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
| Hanya akun aktif dengan role kamad.
| Kepala Madrasah memiliki akses read-only.
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

            'inventaris' =>
                'Inventaris Perangkat',

            'penggunaan' =>
                'Penggunaan Laboratorium',

            'monitoring' =>
                'Monitoring dan Kendala',

            'laporan' =>
                'Laporan Laboratorium',

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
