<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Database\Factories\ProgressionLeconFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressionLecon extends Model
{
    /** @use HasFactory<ProgressionLeconFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'progression_lecons';

    protected $fillable = [
        'inscription_id',
        'lecon_id',
        'terminee',
        'date_completion',
    ];

    protected function casts(): array
    {
        return [
            'terminee' => 'boolean',
            'date_completion' => 'datetime',
        ];
    }

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lecon::class);
    }
}
