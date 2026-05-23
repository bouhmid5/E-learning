<?php

namespace Database\Factories;

use App\Enums\StatutCompte;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/** @extends Factory<Utilisateur> */
class UtilisateurFactory extends Factory
{
    protected $model = Utilisateur::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'email' => fake()->unique()->safeEmail(),
            'mot_de_passe_hash' => Hash::make('password'),
            'telephone' => fake()->phoneNumber(),
            'statut' => StatutCompte::ACTIF,
            'derniere_connexion' => null,
        ];
    }
}

