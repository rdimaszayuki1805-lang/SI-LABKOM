<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    public const KELAS = [
        '7A', '7B', '7C', '7D',
        '8A', '8B', '8C', '8D',
        '9A', '9C', '9D',
    ];

    protected $table = 'siswas';

    protected $fillable = [
        'nama',
        'nisn',
        'kelas',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(
            LogbookSiswa::class,
            'siswa_id'
        );
    }
}
