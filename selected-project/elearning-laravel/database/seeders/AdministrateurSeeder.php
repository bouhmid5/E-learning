<?php

namespace Database\Seeders;

use App\Enums\StatutCompte;
use App\Models\Administrateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministrateurSeeder extends Seeder
{
    public function run(): void
    {
        $administrateurs = [
            [
                'nom' => 'Admin',
                'prenom' => 'Principal',
                'email' => 'admin@elearning.test',
                'mot_de_passe_hash' => Hash::make('password'),
                'niveau_acces' => 'super_admin',
                'statut' => StatutCompte::ACTIF,
            ],
            [
                'nom' => 'Gestionnaire',
                'prenom' => 'Formation',
                'email' => 'gestionnaire@elearning.test',
                'mot_de_passe_hash' => Hash::make('password'),
                'niveau_acces' => 'standard',
                'statut' => StatutCompte::ACTIF,
            ],
        ];

        foreach ($administrateurs as $administrateur) {
            Administrateur::query()->updateOrCreate(
                ['email' => $administrateur['email']],
                $administrateur
            );
        }
    }
}

