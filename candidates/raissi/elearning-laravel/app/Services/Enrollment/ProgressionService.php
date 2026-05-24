<?php

namespace App\Services\Enrollment;

use App\Enums\StatutInscription;
use App\Models\Inscription;
use App\Models\Lecon;
use App\Models\ProgressionLecon;
use DomainException;

class ProgressionService
{
    public function completeLesson(Inscription $inscription, Lecon $lecon): ProgressionLecon
    {
        if ($lecon->cours_id !== $inscription->cours_id) {
            throw new DomainException('Cette lecon ne fait pas partie du cours inscrit.');
        }

        $progression = ProgressionLecon::query()->updateOrCreate(
            [
                'inscription_id' => $inscription->id,
                'lecon_id' => $lecon->id,
            ],
            [
                'terminee' => true,
                'date_completion' => now(),
            ]
        );

        $this->refreshInscriptionProgress($inscription);

        return $progression;
    }

    public function refreshInscriptionProgress(Inscription $inscription): float
    {
        $totalLessons = $inscription->cours()->firstOrFail()->lecons()->count();

        if ($totalLessons === 0) {
            $progression = 0.0;
        } else {
            $completedLessons = $inscription->progressionsLecons()
                ->where('terminee', true)
                ->whereHas('lecon', fn ($query) => $query->where('cours_id', $inscription->cours_id))
                ->count();

            $progression = round(($completedLessons / $totalLessons) * 100, 2);
        }

        $payload = ['progression' => $progression];

        if ($progression >= 100.0) {
            $payload['statut'] = StatutInscription::TERMINEE;
            $payload['date_fin'] = now();
            $payload['certificat_eligible'] = true;
        }

        $inscription->forceFill($payload)->save();

        return $progression;
    }
}
