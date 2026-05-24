<?php

namespace App\Models;

use App\Enums\TypeEvaluation;
use App\Models\Concerns\UsesUuid;
use Database\Factories\EvaluationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    /** @use HasFactory<EvaluationFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'evaluations';

    protected $fillable = [
        'cours_id',
        'titre',
        'description',
        'type_evaluation',
        'score_max',
        'seuil_reussite',
        'ordre',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'type_evaluation' => TypeEvaluation::class,
            'score_max' => 'decimal:2',
            'seuil_reussite' => 'decimal:2',
            'actif' => 'boolean',
        ];
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function criteresCorrection(): HasMany
    {
        return $this->hasMany(CritereCorrection::class);
    }

    public function soumissions(): HasMany
    {
        return $this->hasMany(SoumissionEvaluation::class);
    }
}
