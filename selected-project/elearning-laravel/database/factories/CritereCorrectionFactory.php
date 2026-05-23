<?php

namespace Database\Factories;

use App\Models\CritereCorrection;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CritereCorrection> */
class CritereCorrectionFactory extends Factory
{
    protected $model = CritereCorrection::class;

    public function definition(): array
    {
        return [
            'evaluation_id' => Evaluation::factory(),
            'description' => fake()->sentence(),
            'poids' => 1,
            'valeur_attendue' => null,
            'tolerance' => null,
        ];
    }
}

