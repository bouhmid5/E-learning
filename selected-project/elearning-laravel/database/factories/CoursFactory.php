<?php

namespace Database\Factories;

use App\Enums\StatutCours;
use App\Models\Categorie;
use App\Models\Cours;
use App\Models\Formateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Cours> */
class CoursFactory extends Factory
{
    protected $model = Cours::class;

    public function definition(): array
    {
        return [
            'categorie_id' => Categorie::factory(),
            'formateur_id' => Formateur::factory(),
            'administrateur_validateur_id' => null,
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'niveau' => fake()->randomElement(['debutant', 'intermediaire', 'avance']),
            'langue' => 'fr',
            'prix' => fake()->randomFloat(2, 0, 300),
            'duree_estimee' => fake()->numberBetween(30, 900),
            'image_url' => null,
            'statut' => StatutCours::BROUILLON,
            'date_publication' => null,
            'motif_rejet' => null,
        ];
    }
}

