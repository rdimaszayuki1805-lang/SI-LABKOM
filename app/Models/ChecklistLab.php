<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistLab extends Model
{
    protected $table = 'checklist_labs';

    protected $fillable = [
        'pjlab_id',
        'lokasi',
        'hasil',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'hasil' => 'array',
        ];
    }

    public function pjlab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pjlab_id');
    }
}
