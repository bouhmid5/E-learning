<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $developpement = Categorie::factory()->create([
            'nom' => 'Developpement web',
            'description' => 'Cours de programmation et frameworks web.',
        ]);

        $data = Categorie::factory()->create([
            'nom' => 'Data',
            'description' => 'Analyse de donnees et automatisation.',
        ]);

        Categorie::factory()->create([
            'parent_id' => $developpement->id,
            'nom' => 'Laravel',
            'description' => 'Applications web avec Laravel.',
        ]);

        Categorie::factory()->create([
            'parent_id' => $data->id,
            'nom' => 'SQL',
            'description' => 'Bases de donnees relationnelles.',
        ]);
    }
}

