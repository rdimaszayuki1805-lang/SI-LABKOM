<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN LOGIN SISWA
    |--------------------------------------------------------------------------
    */

    public function showLogin(
        Request $request
    ): View|RedirectResponse {

        $siswaId = $request->session()->get('siswa_id');

        $siswa = $siswaId
            ? Siswa::find($siswaId)
            : null;

        if (
            $siswa &&
            $this->sessionValid($request, $siswa)
        ) {
            return redirect()->route('logbook.siswa');
        }

        $request->session()->forget([
            'siswa_id',
            'siswa_login_at',
            'siswa_auth_hash',
        ]);

        return view('siswa.auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES LOGIN SISWA
    |--------------------------------------------------------------------------
    */

    public function login(Request $request): RedirectResponse
    {
        $request->merge([
            'nisn' => trim(
                (string) $request->input('nisn')
            ),
        ]);

        $validated = $request->validate([
            'nisn' => [
                'required',
                'digits:10',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        $siswa = Siswa::query()
            ->where('nisn', $validated['nisn'])
            ->first();

        if (
            !$siswa ||
            !Hash::check(
                $validated['password'],
                $siswa->password
            )
        ) {
            throw ValidationException::withMessages([
                'nisn' =>
                    'NISN atau password salah. Silakan periksa kembali atau hubungi PJ Lab.',
            ]);
        }

        $request->session()->regenerate();

        $waktuLogin = now();

        $request->session()->put([
            'siswa_id' => $siswa->id,

            'siswa_login_at' =>
                $waktuLogin->toIso8601String(),

            'siswa_auth_hash' => hash(
                'sha256',
                $siswa->password
            ),
        ]);

        return redirect()->route('logbook.siswa');
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT SISWA
    |--------------------------------------------------------------------------
    */

    public function logout(
        Request $request
    ): RedirectResponse {

        $request->session()->forget([
            'siswa_id',
            'siswa_login_at',
            'siswa_auth_hash',
        ]);

        $request->session()->regenerate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('siswa.login')
            ->with(
                'success',
                'Anda berhasil keluar dari Logbook Siswa.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI SESSION SISWA
    |--------------------------------------------------------------------------
    */

    private function sessionValid(
        Request $request,
        Siswa $siswa
    ): bool {

        $hashSession = $request
            ->session()
            ->get('siswa_auth_hash');

        $waktuLogin = $request
            ->session()
            ->get('siswa_login_at');

        return is_string($hashSession)
            && is_string($waktuLogin)
            && hash_equals(
                hash('sha256', $siswa->password),
                $hashSession
            );
    }
}
