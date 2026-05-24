<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        Evaluation::query()->each(function (Evaluation $evaluation): void {
            Question::factory()->count(3)->create([
                'evaluation_id' => $evaluation->id,
            ]);
        });
    }
}

