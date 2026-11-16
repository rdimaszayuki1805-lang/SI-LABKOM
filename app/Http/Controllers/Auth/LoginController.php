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
    | Digunakan oleh:
    | - PJ Lab
    | - Guru
    | - Kepala Madrasah
    |
    | Login siswa menggunakan halaman terpisah.
    */

    public function showLogin(): View
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | 2. MEMPROSES LOGIN PENGELOLA
    |--------------------------------------------------------------------------
    | Login menggunakan email dan password.
    | Hanya akun aktif yang diizinkan masuk.
    */

    public function login(Request $request): RedirectResponse
    {
        /*
        | Validasi input form
        */

        $validated = $request->validate([
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
            trim($validated['email'])
        );

        /*
        | Proses autentikasi Laravel.
        | Akun nonaktif akan ditolak.
        */

        $authenticated = Auth::attempt([
            'email' => $email,
            'password' => $validated['password'],
            'is_active' => 1,
        ], $request->boolean('remember'));

        /*
        | Jika email/password salah atau akun tidak aktif
        */

        if (!$authenticated) {
            throw ValidationException::withMessages([
                'email' =>
                    'Email atau password salah, atau akun tidak aktif.',
            ]);
        }

        /*
        | Ambil pengguna yang berhasil login
        */

        /** @var \App\Models\User $user */
        $user = Auth::user();

        /*
        | Batasi akses login pengelola.
        | Akun siswa tidak boleh login di sini.
        */

        $allowedRoles = [
            User::ROLE_PJLAB,
            User::ROLE_GURU,
            User::ROLE_KAMAD,
        ];

        if (!in_array($user->role, $allowedRoles, true)) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' =>
                    'Akun tidak memiliki akses login pengelola.',
            ]);
        }

        /*
        | Regenerasi session untuk mencegah
        | session fixation.
        */

        $request->session()->regenerate();

        /*
        | Redirect langsung berdasarkan role.
        */

        return redirect()->route(
            $this->dashboardRouteName($user)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 3. MENENTUKAN DASHBOARD BERDASARKAN ROLE
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
        | Keluar dari autentikasi Laravel
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
        | Kembali ke halaman login
        */

        return redirect()->route('login');
    }
}
