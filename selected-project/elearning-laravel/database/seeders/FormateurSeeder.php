<?php

namespace Database\Seeders;

use App\Enums\StatutCompte;
use App\Models\Administrateur;
use App\Models\Formateur;
use Illuminate\Database\Seeder;

class FormateurSeeder extends Seeder
{
    public function run(): void
    {
        $administrateur = Administrateur::query()->first() ?? Administrateur::factory()->create();

        Formateur::factory()->count(3)->create([
            'administrateur_validateur_id' => $administrateur->id,
            'statut_validation' => StatutCompte::ACTIF,
        ]);
    }
}

