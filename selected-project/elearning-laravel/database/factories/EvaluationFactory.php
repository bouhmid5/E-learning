<?php

namespace Database\Factories;

use App\Enums\TypeEvaluation;
use App\Models\Cours;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Evaluation> */
class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        return [
            'cours_id' => Cours::factory(),
            'titre' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'type_evaluation' => TypeEvaluation::QUIZ,
            'score_max' => 100,
            'seuil_reussite' => 60,
            'ordre' => fake()->numberBetween(1, 10),
            'actif' => true,
        ];
    }
}

