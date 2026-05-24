<?php

namespace Database\Factories;

use App\Enums\StatutInscription;
use App\Models\Candidat;
use App\Models\Cours;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Inscription> */
class InscriptionFactory extends Factory
{
    protected $model = Inscription::class;

    public function definition(): array
    {
        return [
            'candidat_id' => Candidat::factory(),
            'cours_id' => Cours::factory(),
            'date_inscription' => now(),
            'progression' => 0,
            'statut' => StatutInscription::EN_COURS,
            'date_fin' => null,
            'certificat_eligible' => false,
        ];
    }
}

