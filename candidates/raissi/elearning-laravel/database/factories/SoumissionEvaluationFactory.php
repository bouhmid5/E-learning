<?php

namespace Database\Factories;

use App\Enums\StatutSoumission;
use App\Models\Candidat;
use App\Models\Evaluation;
use App\Models\SoumissionEvaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<SoumissionEvaluation> */
class SoumissionEvaluationFactory extends Factory
{
    protected $model = SoumissionEvaluation::class;

    public function definition(): array
    {
        return [
            'candidat_id' => Candidat::factory(),
            'evaluation_id' => Evaluation::factory(),
            'date_debut' => now(),
            'date_soumission' => null,
            'numero_tentative' => 1,
            'score_obtenu' => null,
            'reussi' => false,
            'statut' => StatutSoumission::SOUMISE,
            'feedback_automatique' => null,
        ];
    }
}

