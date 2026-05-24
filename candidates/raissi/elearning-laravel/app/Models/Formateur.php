<?php

namespace App\Models;

use App\Enums\StatutCompte;
use App\Models\Concerns\UsesUuid;
use Database\Factories\FormateurFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formateur extends Model
{
    /** @use HasFactory<FormateurFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'formateurs';

    protected $fillable = [
        'utilisateur_id',
        'administrateur_validateur_id',
        'specialite',
        'biographie',
        'statut_validation',
    ];

    protected function casts(): array
    {
        return [
            'statut_validation' => StatutCompte::class,
        ];
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function administrateurValidateur(): BelongsTo
    {
        return $this->belongsTo(Administrateur::class, 'administrateur_validateur_id');
    }

    public function justificatifs(): HasMany
    {
        return $this->hasMany(JustificatifFormateur::class);
    }

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class);
    }
}
