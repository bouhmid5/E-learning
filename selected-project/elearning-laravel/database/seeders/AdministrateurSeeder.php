<?php

namespace Database\Seeders;

use App\Models\Administrateur;
use Illuminate\Database\Seeder;

class AdministrateurSeeder extends Seeder
{
    public function run(): void
    {
        Administrateur::factory()->count(2)->create();
    }
}

