<?php

namespace App\Models;

use App\Enums\StatutSoumission;
use App\Models\Concerns\UsesUuid;
use Database\Factories\SoumissionEvaluationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoumissionEvaluation extends Model
{
    /** @use HasFactory<SoumissionEvaluationFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'soumission_evaluations';

    protected $fillable = [
        'candidat_id',
        'evaluation_id',
        'date_debut',
        'date_soumission',
        'numero_tentative',
        'score_obtenu',
        'reussi',
        'statut',
        'feedback_automatique',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'datetime',
            'date_soumission' => 'datetime',
            'score_obtenu' => 'decimal:2',
            'reussi' => 'boolean',
            'statut' => StatutSoumission::class,
        ];
    }

    public function candidat(): BelongsTo
    {
        return $this->belongsTo(Candidat::class);
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function reponsesCandidats(): HasMany
    {
        return $this->hasMany(ReponseCandidat::class);
    }
}
