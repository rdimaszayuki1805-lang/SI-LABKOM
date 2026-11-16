<?php

namespace App\Http\Controllers\PjLab;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR PENGGUNA
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $pengguna = User::query()
            ->whereIn('role', [
                User::ROLE_PJLAB,
                User::ROLE_GURU,
                User::ROLE_KAMAD,
            ])
            ->orderByRaw(
                "CASE WHEN role = 'pjlab' THEN 0 ELSE 1 END"
            )
            ->orderBy('name')
            ->get();

        return view('pjlab.pengguna.index', [
            'pengguna' => $pengguna,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH PENGGUNA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeEmail($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],

            'role' => [
                'required',
                Rule::in([
                    User::ROLE_GURU,
                    User::ROLE_KAMAD,
                ]),
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        User::create([
            'name' => trim($validated['name']),
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $validated['is_active'],
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        return redirect()
            ->route('pjlab.pengguna.index')
            ->with(
                'success',
                'Pengguna baru berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT PENGGUNA
    |--------------------------------------------------------------------------
    | Password boleh kosong.
    | Jika dikosongkan, password lama dipertahankan.
    */

    public function update(
        Request $request,
        User $user
    ): RedirectResponse {

        $this->ensureManageable($user);

        $this->normalizeEmail($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'role' => [
                'required',
                Rule::in([
                    User::ROLE_GURU,
                    User::ROLE_KAMAD,
                ]),
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->name = trim($validated['name']);

        $user->email = $validated['email'];

        $user->role = $validated['role'];

        $user->is_active = (bool) $validated['is_active'];

        /*
        | Jika PJ Lab mengisi password baru,
        | password lama diganti.
        */

        if (!empty($validated['password'])) {

            $user->password = Hash::make(
                $validated['password']
            );

            // Token "ingat saya" yang lama dibatalkan.
            $user->remember_token = null;
        }

        $user->save();

        return redirect()
            ->route('pjlab.pengguna.index')
            ->with(
                'success',
                'Data pengguna berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS PENGGUNA
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureManageable($user);

        $user->delete();

        return redirect()
            ->route('pjlab.pengguna.index')
            ->with(
                'success',
                'Pengguna berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PEMBATASAN AKUN YANG BOLEH DIKELOLA
    |--------------------------------------------------------------------------
    */

    private function ensureManageable(User $user): void
    {
        abort_if(
            $user->is_protected ||
            !in_array($user->role, [
                User::ROLE_GURU,
                User::ROLE_KAMAD,
            ], true),
            403,
            'Akun ini tidak boleh diubah atau dihapus.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALISASI EMAIL
    |--------------------------------------------------------------------------
    */

    private function normalizeEmail(Request $request): void
    {
        $request->merge([
            'email' => mb_strtolower(
                trim((string) $request->input('email'))
            ),
        ]);
    }
}
