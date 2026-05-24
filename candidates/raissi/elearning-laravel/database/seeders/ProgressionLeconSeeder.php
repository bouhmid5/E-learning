<?php

namespace Database\Seeders;

use App\Models\Inscription;
use App\Models\ProgressionLecon;
use Illuminate\Database\Seeder;

class ProgressionLeconSeeder extends Seeder
{
    public function run(): void
    {
        Inscription::query()->with('cours.lecons')->each(function (Inscription $inscription): void {
            foreach ($inscription->cours->lecons as $index => $lecon) {
                ProgressionLecon::factory()->create([
                    'inscription_id' => $inscription->id,
                    'lecon_id' => $lecon->id,
                    'terminee' => $index < 2,
                    'date_completion' => $index < 2 ? now()->subDays(2 - $index) : null,
                ]);
            }
        });
    }
}

