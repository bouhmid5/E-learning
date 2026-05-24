<?php

namespace Database\Factories;

use App\Models\OptionReponse;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OptionReponse> */
class OptionReponseFactory extends Factory
{
    protected $model = OptionReponse::class;

    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'texte' => fake()->sentence(),
            'est_correcte' => false,
        ];
    }
}

