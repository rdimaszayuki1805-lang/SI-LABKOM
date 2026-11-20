<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanLab extends Model
{
    protected $table = 'peminjaman_labs';

    protected $fillable = [
        'guru_id',
        'kelas',
        'mata_pelajaran',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'tujuan',
        'status',
        'catatan_pjlab',
        'status_email',
        'email_terkirim_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'email_terkirim_at' => 'datetime',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
