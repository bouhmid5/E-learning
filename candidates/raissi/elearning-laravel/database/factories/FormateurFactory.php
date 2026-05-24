<?php

namespace Database\Factories;

use App\Enums\StatutCompte;
use App\Models\Formateur;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Formateur> */
class FormateurFactory extends Factory
{
    protected $model = Formateur::class;

    public function definition(): array
    {
        return [
            'utilisateur_id' => Utilisateur::factory(),
            'administrateur_validateur_id' => null,
            'specialite' => fake()->jobTitle(),
            'biographie' => fake()->paragraph(),
            'statut_validation' => StatutCompte::EN_ATTENTE,
        ];
    }
}

