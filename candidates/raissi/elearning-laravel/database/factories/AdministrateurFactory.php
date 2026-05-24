<?php

namespace Database\Factories;

use App\Enums\StatutCompte;
use App\Models\Administrateur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/** @extends Factory<Administrateur> */
class AdministrateurFactory extends Factory
{
    protected $model = Administrateur::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'mot_de_passe_hash' => Hash::make('password'),
            'niveau_acces' => 'standard',
            'statut' => StatutCompte::ACTIF,
        ];
    }
}

