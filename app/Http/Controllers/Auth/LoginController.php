<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. MENAMPILKAN HALAMAN LOGIN
    |--------------------------------------------------------------------------
    | Halaman login khusus:
    | - PJ Lab
    | - Guru
    | - Kepala Madrasah
    |
    | Login siswa menggunakan halaman dan controller terpisah.
    */

    public function showLogin(): View
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | 2. MEMPROSES LOGIN
    |--------------------------------------------------------------------------
    | Autentikasi menggunakan email dan password.
    */

    public function login(Request $request): RedirectResponse
    {
        /*
        | Validasi input
        */

        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);


        /*
        | Normalisasi email
        */

        $email = mb_strtolower(
            trim($credentials['email'])
        );


        /*
        | Autentikasi Laravel
        */

        $success = Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
        ], $request->boolean('remember'));


        /*
        | Jika email atau password salah
        */

        if (!$success) {

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);

        }


        /*
        | Ambil pengguna yang berhasil login
        */

        $user = Auth::user();


        /*
        | Batasi role login pengelola
        */

        $allowedRoles = [
            User::ROLE_PJLAB,
            User::ROLE_GURU,
            User::ROLE_KAMAD,
        ];


        /*
        | Jika role tidak diizinkan
        | Contoh: siswa mencoba login melalui halaman pengelola
        */

        if (!in_array($user->role, $allowedRoles, true)) {

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);

        }


        /*
        | Regenerasi session untuk keamanan
        */

        $request->session()->regenerate();


        /*
        | Arahkan langsung ke dashboard berdasarkan role
        */

        return redirect()->route(
            $this->dashboardRouteName($user)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 3. MENENTUKAN ROUTE DASHBOARD BERDASARKAN ROLE
    |--------------------------------------------------------------------------
    */

    private function dashboardRouteName(User $user): string
    {
        return match ($user->role) {

            User::ROLE_PJLAB => 'pjlab.dashboard',

            User::ROLE_GURU => 'guru.dashboard',

            User::ROLE_KAMAD => 'kamad.dashboard',

            default => 'login',

        };
    }


    /*
    |--------------------------------------------------------------------------
    | 4. LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request): RedirectResponse
    {
        /*
        | Hapus autentikasi pengguna
        */

        Auth::logout();


        /*
        | Hapus session lama
        */

        $request->session()->invalidate();


        /*
        | Perbarui CSRF token
        */

        $request->session()->regenerateToken();


        /*
        | Kembali ke halaman login pengelola
        */

        return redirect()->route('login');
    }
}
