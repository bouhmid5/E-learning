<?php

namespace Database\Factories;

use App\Models\Inscription;
use App\Models\Lecon;
use App\Models\ProgressionLecon;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProgressionLecon> */
class ProgressionLeconFactory extends Factory
{
    protected $model = ProgressionLecon::class;

    public function definition(): array
    {
        return [
            'inscription_id' => Inscription::factory(),
            'lecon_id' => Lecon::factory(),
            'terminee' => false,
            'date_completion' => null,
        ];
    }
}

