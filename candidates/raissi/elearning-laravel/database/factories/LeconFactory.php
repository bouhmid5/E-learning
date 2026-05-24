<?php

namespace Database\Factories;

use App\Models\Cours;
use App\Models\Lecon;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Lecon> */
class LeconFactory extends Factory
{
    protected $model = Lecon::class;

    public function definition(): array
    {
        return [
            'cours_id' => Cours::factory(),
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'ordre' => fake()->numberBetween(1, 10),
            'duree_estimee' => fake()->numberBetween(5, 90),
        ];
    }
}

