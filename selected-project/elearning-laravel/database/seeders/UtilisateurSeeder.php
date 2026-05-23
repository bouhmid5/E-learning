<?php

namespace Database\Seeders;

use App\Enums\StatutCompte;
use App\Models\Administrateur;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    public function run(): void
    {
        $administrateur = Administrateur::query()
            ->where('email', 'admin@elearning.test')
            ->first();

        $utilisateurs = [
            [
                'administrateur_id' => $administrateur?->id,
                'nom' => 'Ben Ali',
                'prenom' => 'Yasmine',
                'email' => 'yasmine.benali@elearning.test',
                'mot_de_passe_hash' => Hash::make('password'),
                'telephone' => '+216 20 111 222',
                'statut' => StatutCompte::ACTIF,
                'derniere_connexion' => null,
            ],
            [
                'administrateur_id' => $administrateur?->id,
                'nom' => 'Mansouri',
                'prenom' => 'Karim',
                'email' => 'karim.mansouri@elearning.test',
                'mot_de_passe_hash' => Hash::make('password'),
                'telephone' => '+216 21 333 444',
                'statut' => StatutCompte::ACTIF,
                'derniere_connexion' => null,
            ],
            [
                'administrateur_id' => $administrateur?->id,
                'nom' => 'Trabelsi',
                'prenom' => 'Nour',
                'email' => 'nour.trabelsi@elearning.test',
                'mot_de_passe_hash' => Hash::make('password'),
                'telephone' => '+216 22 555 666',
                'statut' => StatutCompte::EN_ATTENTE,
                'derniere_connexion' => null,
            ],
        ];

        foreach ($utilisateurs as $utilisateur) {
            Utilisateur::query()->updateOrCreate(
                ['email' => $utilisateur['email']],
                $utilisateur
            );
        }
    }
}

