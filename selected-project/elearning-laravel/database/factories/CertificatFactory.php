<?php

namespace Database\Factories;

use App\Models\Certificat;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Certificat> */
class CertificatFactory extends Factory
{
    protected $model = Certificat::class;

    public function definition(): array
    {
        return [
            'inscription_id' => Inscription::factory(),
            'code_verification' => 'CERT-'.fake()->unique()->bothify('####-????'),
            'date_generation' => now(),
            'fichier_url' => null,
            'actif' => true,
        ];
    }
}

