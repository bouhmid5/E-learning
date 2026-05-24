<?php

namespace Database\Seeders;

use App\Models\OptionReponse;
use App\Models\Question;
use Illuminate\Database\Seeder;

class OptionReponseSeeder extends Seeder
{
    public function run(): void
    {
        Question::query()->each(function (Question $question): void {
            OptionReponse::factory()->create([
                'question_id' => $question->id,
                'texte' => 'Reponse correcte',
                'est_correcte' => true,
            ]);

            OptionReponse::factory()->count(3)->create([
                'question_id' => $question->id,
                'est_correcte' => false,
            ]);
        });
    }
}

