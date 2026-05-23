<?php

namespace Database\Seeders;

use App\Models\ReponseCandidat;
use App\Models\SoumissionEvaluation;
use Illuminate\Database\Seeder;

class ReponseCandidatSeeder extends Seeder
{
    public function run(): void
    {
        SoumissionEvaluation::query()
            ->with('evaluation.questions')
            ->each(function (SoumissionEvaluation $soumission): void {
                foreach ($soumission->evaluation->questions as $question) {
                    ReponseCandidat::factory()->create([
                        'soumission_evaluation_id' => $soumission->id,
                        'question_id' => $question->id,
                        'valeur' => 'Reponse correcte',
                        'est_correcte' => true,
                        'points_obtenus' => $question->points,
                    ]);
                }
            });
    }
}

