<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MENAMPILKAN LOGIN
    |--------------------------------------------------------------------------
    */

    public function showLogin(): View
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request): RedirectResponse
    {
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

        $email = mb_strtolower(
            trim($credentials['email'])
        );

        $success = Auth::attempt([
            'email' => $email,
            'password' => $credentials['password'],
        ], $request->boolean('remember'));

        if (!$success) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();

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
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(
            $this->dashboardRoute($user)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT BERDASARKAN ROLE
    |--------------------------------------------------------------------------
    */

    private function dashboardRoute(User $user): string
    {
        return match ($user->role) {
            User::ROLE_PJLAB => route('pjlab.dashboard'),
            User::ROLE_GURU => route('guru.dashboard'),
            User::ROLE_KAMAD => route('kamad.dashboard'),
            default => route('login'),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
