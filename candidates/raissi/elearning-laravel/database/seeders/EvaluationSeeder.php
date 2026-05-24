<?php

namespace Database\Seeders;

use App\Models\Cours;
use App\Models\Evaluation;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        Cours::query()->each(function (Cours $cours): void {
            Evaluation::factory()->create([
                'cours_id' => $cours->id,
                'ordre' => 1,
            ]);
        });
    }
}

