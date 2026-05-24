<?php

namespace Database\Seeders;

use App\Enums\StatutCompte;
use App\Models\Administrateur;
use App\Models\Candidat;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CandidatSeeder extends Seeder
{
    public function run(): void
    {
        $administrateur = Administrateur::query()
            ->where('email', 'admin@elearning.test')
            ->first();

        $candidats = [
            [
                'utilisateur' => [
                    'nom' => 'Ben Ali',
                    'prenom' => 'Yasmine',
                    'email' => 'yasmine.benali@elearning.test',
                    'mot_de_passe_hash' => Hash::make('password'),
                    'telephone' => '+216 20 111 222',
                    'statut' => StatutCompte::ACTIF,
                    'derniere_connexion' => null,
                ],
                'candidat' => [
                    'niveau' => 'debutant',
                    'objectif_apprentissage' => 'Apprendre le developpement web avec un parcours guide.',
                ],
            ],
            [
                'utilisateur' => [
                    'nom' => 'Mansouri',
                    'prenom' => 'Karim',
                    'email' => 'karim.mansouri@elearning.test',
                    'mot_de_passe_hash' => Hash::make('password'),
                    'telephone' => '+216 21 333 444',
                    'statut' => StatutCompte::ACTIF,
                    'derniere_connexion' => null,
                ],
                'candidat' => [
                    'niveau' => 'intermediaire',
                    'objectif_apprentissage' => 'Renforcer les bases SQL et Laravel.',
                ],
            ],
        ];

        foreach ($candidats as $data) {
            $utilisateur = Utilisateur::query()->updateOrCreate(
                ['email' => $data['utilisateur']['email']],
                [
                    ...$data['utilisateur'],
                    'administrateur_id' => $administrateur?->id,
                ]
            );

            Candidat::query()->updateOrCreate(
                ['utilisateur_id' => $utilisateur->id],
                $data['candidat']
            );
        }

        Candidat::factory()->count(3)->create();
    }
}
