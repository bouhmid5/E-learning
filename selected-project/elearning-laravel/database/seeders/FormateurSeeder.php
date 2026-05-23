<?php

namespace Database\Seeders;

use App\Enums\StatutCompte;
use App\Models\Administrateur;
use App\Models\Formateur;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FormateurSeeder extends Seeder
{
    public function run(): void
    {
        $administrateur = Administrateur::query()
            ->where('email', 'admin@elearning.test')
            ->first() ?? Administrateur::query()->first();

        $formateurs = [
            [
                'utilisateur' => [
                    'nom' => 'Haddad',
                    'prenom' => 'Sami',
                    'email' => 'sami.haddad@elearning.test',
                    'mot_de_passe_hash' => Hash::make('password'),
                    'telephone' => '+216 23 100 200',
                    'statut' => StatutCompte::ACTIF,
                    'derniere_connexion' => null,
                ],
                'formateur' => [
                    'specialite' => 'Laravel et architecture web',
                    'biographie' => 'Formateur specialise dans le developpement backend avec Laravel, les API REST et les bonnes pratiques de conception.',
                    'statut_validation' => StatutCompte::ACTIF,
                ],
            ],
            [
                'utilisateur' => [
                    'nom' => 'Gharbi',
                    'prenom' => 'Ines',
                    'email' => 'ines.gharbi@elearning.test',
                    'mot_de_passe_hash' => Hash::make('password'),
                    'telephone' => '+216 24 300 400',
                    'statut' => StatutCompte::ACTIF,
                    'derniere_connexion' => null,
                ],
                'formateur' => [
                    'specialite' => 'Bases de donnees SQL',
                    'biographie' => 'Formatrice en modelisation relationnelle, requetes SQL, optimisation et administration de bases de donnees.',
                    'statut_validation' => StatutCompte::ACTIF,
                ],
            ],
            [
                'utilisateur' => [
                    'nom' => 'Mejri',
                    'prenom' => 'Omar',
                    'email' => 'omar.mejri@elearning.test',
                    'mot_de_passe_hash' => Hash::make('password'),
                    'telephone' => '+216 25 500 600',
                    'statut' => StatutCompte::ACTIF,
                    'derniere_connexion' => null,
                ],
                'formateur' => [
                    'specialite' => 'Frontend JavaScript',
                    'biographie' => 'Formateur frontend autour de JavaScript, interfaces responsives, composants reutilisables et integration API.',
                    'statut_validation' => StatutCompte::EN_ATTENTE,
                ],
            ],
        ];

        foreach ($formateurs as $data) {
            $utilisateur = Utilisateur::query()->updateOrCreate(
                ['email' => $data['utilisateur']['email']],
                [
                    ...$data['utilisateur'],
                    'administrateur_id' => $administrateur?->id,
                ]
            );

            Formateur::query()->updateOrCreate(
                ['utilisateur_id' => $utilisateur->id],
                [
                    ...$data['formateur'],
                    'administrateur_validateur_id' => $administrateur?->id,
                ]
            );
        }
    }
}

