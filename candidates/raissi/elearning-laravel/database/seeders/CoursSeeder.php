<?php

namespace Database\Seeders;

use App\Enums\StatutCours;
use App\Enums\StatutCompte;
use App\Models\Administrateur;
use App\Models\Categorie;
use App\Models\Cours;
use App\Models\Formateur;
use Illuminate\Database\Seeder;

class CoursSeeder extends Seeder
{
    public function run(): void
    {
        $administrateur = Administrateur::query()->first();
        $categories = Categorie::query()->get();
        $formateurs = Formateur::query()
            ->where('statut_validation', StatutCompte::ACTIF->value)
            ->get();

        if ($categories->isEmpty()) {
            $categories = Categorie::factory()->count(2)->create();
        }

        if ($formateurs->isEmpty()) {
            $formateurs = Formateur::factory()->count(2)->create([
                'statut_validation' => StatutCompte::ACTIF,
            ]);
        }

        foreach ($formateurs as $index => $formateur) {
            Cours::factory()->count(2)->create([
                'categorie_id' => $categories[$index % $categories->count()]->id,
                'formateur_id' => $formateur->id,
                'administrateur_validateur_id' => $administrateur?->id,
                'statut' => StatutCours::PUBLIE,
                'date_publication' => now(),
            ]);
        }
    }
}
