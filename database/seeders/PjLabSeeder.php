<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PjLabSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $existing = User::query()
                ->where('email', User::EMAIL_PJLAB)
                ->first();

            if ($existing) {
                if (
                    !$existing->isPjLab() ||
                    !$existing->is_protected
                ) {
                    throw new RuntimeException(
                        'Email PJ Lab sudah digunakan oleh akun yang tidak sesuai.'
                    );
                }

                return;
            }

            $password = config(
                'silabkom.pjlab_initial_password'
            );

            if (!is_string($password) || $password === '') {
                throw new RuntimeException(
                    'PJLAB_INITIAL_PASSWORD belum diatur di .env.'
                );
            }

            $user = new User();

            $user->forceFill([
                'name' => 'Penanggung Jawab Laboratorium',
                'email' => User::EMAIL_PJLAB,
                'password' => $password,
                'role' => User::ROLE_PJLAB,
                'is_protected' => true,
            ]);

            $user->save();
        });
    }
}
