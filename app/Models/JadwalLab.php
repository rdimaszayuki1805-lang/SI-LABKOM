<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalLab extends Model
{
    protected $table = 'jadwal_labs';

    protected $fillable = [
        'hari',
        'kelas',
        'jam_mulai',
        'jam_selesai',
        'mata_pelajaran',
        'guru',
        'lokasi',
        'status',
    ];
}
