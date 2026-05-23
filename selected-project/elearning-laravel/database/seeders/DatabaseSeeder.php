<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Candidat;
use App\Models\Cours;
use App\Models\Formateur;
use App\Models\Inscription;
use App\Models\Lecon;
use App\Models\Ressource;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categorie = Categorie::factory()->create([
            'nom' => 'Developpement web',
        ]);

        $formateur = Formateur::factory()->create();
        $candidat = Candidat::factory()->create();

        $cours = Cours::factory()->create([
            'categorie_id' => $categorie->id,
            'formateur_id' => $formateur->id,
            'titre' => 'Introduction a Laravel',
        ]);

        $lecon = Lecon::factory()->create([
            'cours_id' => $cours->id,
            'titre' => 'Premiers pas',
            'ordre' => 1,
        ]);

        Ressource::factory()->create([
            'lecon_id' => $lecon->id,
        ]);

        Inscription::factory()->create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
        ]);
    }
}
