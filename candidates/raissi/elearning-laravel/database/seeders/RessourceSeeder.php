<?php

namespace Database\Seeders;

use App\Models\Lecon;
use App\Models\Ressource;
use Illuminate\Database\Seeder;

class RessourceSeeder extends Seeder
{
    public function run(): void
    {
        Lecon::query()->each(function (Lecon $lecon): void {
            Ressource::factory()->count(2)->sequence(
                ['ordre' => 1],
                ['ordre' => 2],
            )->create([
                'lecon_id' => $lecon->id,
            ]);
        });
    }
}

