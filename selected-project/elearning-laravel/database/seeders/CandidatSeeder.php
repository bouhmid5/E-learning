<?php

namespace Database\Seeders;

use App\Models\Candidat;
use Illuminate\Database\Seeder;

class CandidatSeeder extends Seeder
{
    public function run(): void
    {
        Candidat::factory()->count(5)->create();
    }
}

