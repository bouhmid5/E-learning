<?php

namespace Database\Seeders;

use App\Enums\StatutInscription;
use App\Models\Certificat;
use App\Models\Inscription;
use Illuminate\Database\Seeder;

class CertificatSeeder extends Seeder
{
    public function run(): void
    {
        Inscription::query()
            ->take(5)
            ->get()
            ->each(function (Inscription $inscription): void {
                $inscription->forceFill([
                    'progression' => 100,
                    'statut' => StatutInscription::TERMINEE,
                    'date_fin' => now(),
                    'certificat_eligible' => true,
                ])->save();

                Certificat::factory()->create([
                    'inscription_id' => $inscription->id,
                    'date_generation' => now(),
                ]);
            });
    }
}

