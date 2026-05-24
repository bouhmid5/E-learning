<?php

namespace Database\Factories;

use App\Models\Candidat;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Candidat> */
class CandidatFactory extends Factory
{
    protected $model = Candidat::class;

    public function definition(): array
    {
        return [
            'utilisateur_id' => Utilisateur::factory(),
            'niveau' => fake()->randomElement(['debutant', 'intermediaire', 'avance']),
            'objectif_apprentissage' => fake()->sentence(),
        ];
    }
}

