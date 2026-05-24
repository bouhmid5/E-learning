<?php

namespace Database\Seeders;

use App\Models\Candidat;
use App\Models\Cours;
use App\Models\Inscription;
use Illuminate\Database\Seeder;

class InscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $cours = Cours::query()->take(3)->get();

        Candidat::query()->each(function (Candidat $candidat) use ($cours): void {
            foreach ($cours as $course) {
                Inscription::factory()->create([
                    'candidat_id' => $candidat->id,
                    'cours_id' => $course->id,
                ]);
            }
        });
    }
}

