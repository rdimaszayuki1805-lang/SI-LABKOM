<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\ValidationException;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | ROLE PENGGUNA
    |--------------------------------------------------------------------------
    */

    public const ROLE_PJLAB = 'pjlab';
    public const ROLE_GURU = 'guru';
    public const ROLE_KAMAD = 'kamad';
    public const ROLE_SISWA = 'siswa';

    /*
    |--------------------------------------------------------------------------
    | AKUN UTAMA
    |--------------------------------------------------------------------------
    */

    public const EMAIL_PJLAB = 'pjlab@gmail.com';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /*
    |--------------------------------------------------------------------------
    | HIDDEN ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTE CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_protected' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECK
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
    | AKUN UTAMA TERLINDUNGI
    |--------------------------------------------------------------------------
    */

    public function isMainPjLab(): bool
    {
        return $this->email === self::EMAIL_PJLAB
            || $this->is_protected === true;
    }

    /*
    |--------------------------------------------------------------------------
    | PROTEKSI MODEL
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if ($user->email === self::EMAIL_PJLAB) {
                if (
                    $user->role !== self::ROLE_PJLAB ||
                    !$user->is_protected
                ) {
                    throw ValidationException::withMessages([
                        'email' => 'Email ini khusus akun utama PJ Lab.',
                    ]);
                }
            }
        });

        static::updating(function (User $user) {
            $wasProtected =
                $user->getRawOriginal('email') === self::EMAIL_PJLAB
                || (bool) $user->getRawOriginal('is_protected');

            if (!$wasProtected) {
                if ($user->email === self::EMAIL_PJLAB) {
                    throw ValidationException::withMessages([
                        'email' => 'Email utama PJ Lab tidak dapat digunakan.',
                    ]);
                }

                return;
            }

            /*
             * Hanya remember_token yang boleh berubah,
             * agar fitur Ingat Saya tetap berfungsi.
             */
            $changed = array_keys($user->getDirty());

            $notAllowed = array_diff(
                $changed,
                ['remember_token']
            );

            if (!empty($notAllowed)) {
                throw ValidationException::withMessages([
                    'user' => 'Akun utama PJ Lab tidak dapat diubah.',
                ]);
            }
        });

        static::deleting(function (User $user) {
            if ($user->isMainPjLab()) {
                throw ValidationException::withMessages([
                    'user' => 'Akun utama PJ Lab tidak dapat dihapus.',
                ]);
            }
        });
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
