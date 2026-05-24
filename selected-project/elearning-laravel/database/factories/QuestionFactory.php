<?php

namespace Database\Factories;

use App\Enums\TypeQuestion;
use App\Models\Evaluation;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Question> */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'evaluation_id' => Evaluation::factory(),
            'enonce' => fake()->sentence().' ?',
            'type' => TypeQuestion::QCM,
            'points' => 1,
        ];
    }
}

