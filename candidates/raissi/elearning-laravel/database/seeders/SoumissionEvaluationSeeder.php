<?php

namespace Database\Seeders;

use App\Enums\StatutSoumission;
use App\Models\Inscription;
use App\Models\SoumissionEvaluation;
use Illuminate\Database\Seeder;

class SoumissionEvaluationSeeder extends Seeder
{
    public function run(): void
    {
        Inscription::query()->with('cours.evaluations')->each(function (Inscription $inscription): void {
            foreach ($inscription->cours->evaluations as $evaluation) {
                SoumissionEvaluation::factory()->create([
                    'candidat_id' => $inscription->candidat_id,
                    'evaluation_id' => $evaluation->id,
                    'date_soumission' => now(),
                    'score_obtenu' => 75,
                    'reussi' => true,
                    'statut' => StatutSoumission::REUSSIE,
                ]);
            }
        });
    }
}

