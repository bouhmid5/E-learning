<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\ReponseCandidat;
use App\Models\SoumissionEvaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ReponseCandidat> */
class ReponseCandidatFactory extends Factory
{
    protected $model = ReponseCandidat::class;

    public function definition(): array
    {
        return [
            'soumission_evaluation_id' => SoumissionEvaluation::factory(),
            'question_id' => Question::factory(),
            'valeur' => fake()->sentence(),
            'est_correcte' => null,
            'points_obtenus' => 0,
        ];
    }
}

