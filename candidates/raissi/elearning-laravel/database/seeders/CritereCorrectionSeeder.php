<?php

namespace Database\Seeders;

use App\Models\CritereCorrection;
use App\Models\Evaluation;
use Illuminate\Database\Seeder;

class CritereCorrectionSeeder extends Seeder
{
    public function run(): void
    {
        Evaluation::query()->each(function (Evaluation $evaluation): void {
            CritereCorrection::factory()->create([
                'evaluation_id' => $evaluation->id,
            ]);
        });
    }
}

