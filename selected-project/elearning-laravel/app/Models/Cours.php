<?php

namespace App\Models;

use App\Enums\StatutCours;
use App\Models\Concerns\UsesUuid;
use Database\Factories\CoursFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Cours extends Model
{
    /** @use HasFactory<CoursFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'cours';

    protected $fillable = [
        'categorie_id',
        'formateur_id',
        'administrateur_validateur_id',
        'titre',
        'description',
        'niveau',
        'langue',
        'prix',
        'duree_estimee',
        'image_url',
        'statut',
        'date_publication',
        'motif_rejet',
    ];

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'statut' => StatutCours::class,
            'date_publication' => 'datetime',
        ];
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class);
    }

    public function administrateurValidateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class, 'administrateur_validateur_id');
    }

    public function lecons(): HasMany
    {
        return $this->hasMany(Lecon::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function scopePublie(Builder $query): Builder
    {
        return $query->where('statut', StatutCours::PUBLIE->value);
    }
}
