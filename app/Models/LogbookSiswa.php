<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookSiswa extends Model
{
    protected $table = 'logbook_siswas';

    protected $fillable = [
        'siswa_id',
        'nomor_komputer',
        'mata_pelajaran',
        'kegiatan',
        'monitor',
        'keyboard',
        'mouse',
        'cpu',
        'jaringan',
        'ada_kendala',
        'kondisi_perangkat',
        'kendala',
        'waktu_login',
    ];

    protected function casts(): array
    {
        return [
            'ada_kendala' => 'boolean',
            'waktu_login' => 'datetime',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
