<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Database\Factories\LeconFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecon extends Model
{
    /** @use HasFactory<LeconFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'lecons';

    protected $fillable = [
        'cours_id',
        'titre',
        'description',
        'ordre',
        'duree_estimee',
    ];

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function ressources(): HasMany
    {
        return $this->hasMany(Ressource::class);
    }

    public function progressionsLecons(): HasMany
    {
        return $this->hasMany(ProgressionLecon::class);
    }
}
