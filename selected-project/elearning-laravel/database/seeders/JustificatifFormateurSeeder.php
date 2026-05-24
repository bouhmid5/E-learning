<?php

namespace Database\Seeders;

use App\Models\Formateur;
use App\Models\JustificatifFormateur;
use Illuminate\Database\Seeder;

class JustificatifFormateurSeeder extends Seeder
{
    public function run(): void
    {
        Formateur::query()->each(function (Formateur $formateur): void {
            JustificatifFormateur::factory()->create([
                'formateur_id' => $formateur->id,
                'administrateur_validateur_id' => $formateur->administrateur_validateur_id,
            ]);
        });
    }
}

