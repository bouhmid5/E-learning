<?php

namespace Database\Factories;

use App\Enums\StatutJustificatif;
use App\Models\Formateur;
use App\Models\JustificatifFormateur;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<JustificatifFormateur> */
class JustificatifFormateurFactory extends Factory
{
    protected $model = JustificatifFormateur::class;

    public function definition(): array
    {
        return [
            'formateur_id' => Formateur::factory(),
            'administrateur_validateur_id' => null,
            'type' => 'certificat',
            'fichier_url' => 'justificatifs/'.fake()->uuid().'.pdf',
            'statut' => StatutJustificatif::EN_ATTENTE,
            'date_depot' => now(),
            'date_validation' => null,
            'commentaire_validation' => null,
        ];
    }
}

