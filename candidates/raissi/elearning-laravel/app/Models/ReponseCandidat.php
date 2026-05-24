<?php

namespace App\Models;

use App\Models\Concerns\UsesUuid;
use Database\Factories\ReponseCandidatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReponseCandidat extends Model
{
    /** @use HasFactory<ReponseCandidatFactory> */
    use HasFactory;
    use SoftDeletes;
    use UsesUuid;

    protected $table = 'reponse_candidats';

    protected $fillable = [
        'soumission_evaluation_id',
        'question_id',
        'valeur',
        'est_correcte',
        'points_obtenus',
    ];

    protected function casts(): array
    {
        return [
            'est_correcte' => 'boolean',
            'points_obtenus' => 'decimal:2',
        ];
    }

    public function soumissionEvaluation(): BelongsTo
    {
        return $this->belongsTo(SoumissionEvaluation::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
