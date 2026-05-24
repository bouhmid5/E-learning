<?php

namespace Database\Factories;

use App\Enums\TypeRessource;
use App\Models\Lecon;
use App\Models\Ressource;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Ressource> */
class RessourceFactory extends Factory
{
    protected $model = Ressource::class;

    public function definition(): array
    {
        return [
            'lecon_id' => Lecon::factory(),
            'titre' => fake()->sentence(3),
            'type' => TypeRessource::DOCUMENT,
            'url' => 'ressources/'.fake()->uuid().'.pdf',
            'ordre' => fake()->numberBetween(1, 10),
            'telechargeable' => true,
            'taille' => fake()->numberBetween(1024, 10485760),
        ];
    }
}

