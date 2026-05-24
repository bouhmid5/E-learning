<?php

namespace Database\Seeders;

use App\Models\Cours;
use App\Models\Lecon;
use Illuminate\Database\Seeder;

class LeconSeeder extends Seeder
{
    public function run(): void
    {
        Cours::query()->each(function (Cours $cours): void {
            Lecon::factory()->count(3)->sequence(
                ['ordre' => 1],
                ['ordre' => 2],
                ['ordre' => 3],
            )->create([
                'cours_id' => $cours->id,
            ]);
        });
    }
}

