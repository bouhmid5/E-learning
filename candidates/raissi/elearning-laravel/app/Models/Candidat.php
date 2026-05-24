<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Database\Factories\CandidatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    /** @use HasFactory<CandidatFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'candidats';

    protected $fillable = [
        'utilisateur_id',
        'niveau',
        'objectif_apprentissage',
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function cours(): BelongsToMany
    {
        return $this->belongsToMany(Cours::class, 'inscriptions')
            ->withPivot(['id', 'date_inscription', 'progression', 'statut', 'date_fin', 'certificat_eligible'])
            ->withTimestamps();
    }

    public function soumissionsEvaluation(): HasMany
    {
        return $this->hasMany(SoumissionEvaluation::class);
    }
}
