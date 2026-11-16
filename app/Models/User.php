<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | KONSTANTA ROLE
    |--------------------------------------------------------------------------
    */

    public const ROLE_PJLAB = 'pjlab';
    public const ROLE_GURU = 'guru';
    public const ROLE_KAMAD = 'kamad';
    public const ROLE_SISWA = 'siswa';

    public const EMAIL_PJLAB = 'pjlab@gmail.com';


    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    | is_protected sengaja tidak dimasukkan.
    | Hanya seeder yang boleh menetapkan akun terlindungi.
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];


    /*
    |--------------------------------------------------------------------------
    | HIDDEN
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_protected' => 'boolean',
            'is_active' => 'boolean',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PERLINDUNGAN AKUN PJ LAB UTAMA
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::updating(function (User $user) {

            // Lindungi akun yang sejak awal ditandai protected.
            if (!$user->getRawOriginal('is_protected')) {
                return;
            }

            // Laravel tetap boleh memperbarui remember_token.
            $allowedFields = ['remember_token'];

            $changedFields = array_keys($user->getDirty());

            $notAllowed = array_diff(
                $changedFields,
                $allowedFields
            );

            if (!empty($notAllowed)) {
                throw new \RuntimeException(
                    'Akun PJ Lab utama tidak boleh diubah.'
                );
            }
        });

        static::deleting(function (User $user) {

            if ($user->is_protected) {
                throw new \RuntimeException(
                    'Akun PJ Lab utama tidak boleh dihapus.'
                );
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER ROLE
    |--------------------------------------------------------------------------
    */

    public function isPjLab(): bool
    {
        return $this->role === self::ROLE_PJLAB;
    }

    public function isGuru(): bool
    {
        return $this->role === self::ROLE_GURU;
    }

    public function isKamad(): bool
    {
        return $this->role === self::ROLE_KAMAD;
    }

    public function isSiswa(): bool
    {
        return $this->role === self::ROLE_SISWA;
    }


    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePjLab(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_PJLAB);
    }

    public function scopeGuru(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_GURU);
    }

    public function scopeKamad(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_KAMAD);
    }

    public function scopeSiswa(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_SISWA);
    }
}
