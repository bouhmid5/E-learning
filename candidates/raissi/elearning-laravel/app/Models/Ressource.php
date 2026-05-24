<?php

namespace App\Models;

use App\Enums\TypeRessource;
use App\Models\Concerns\UsesUuid;
use Database\Factories\RessourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ressource extends Model
{
    /** @use HasFactory<RessourceFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'ressources';

    protected $fillable = [
        'lecon_id',
        'titre',
        'type',
        'url',
        'ordre',
        'telechargeable',
        'taille',
    ];

    protected function casts(): array
    {
        return [
            'type' => TypeRessource::class,
            'telechargeable' => 'boolean',
        ];
    }

    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lecon::class);
    }
}
