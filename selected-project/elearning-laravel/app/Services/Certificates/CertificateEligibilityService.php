<?php

namespace App\Services\Certificates;

use App\Enums\StatutInscription;
use App\Models\Inscription;

class CertificateEligibilityService
{
    public function check(Inscription $inscription): array
    {
        $inscription->loadMissing('cours.evaluations');

        $checks = [
            'progression_complete' => (float) $inscription->progression >= 100.0,
            'not_abandoned' => $inscription->statut !== StatutInscription::ABANDONNEE,
            'conditions_validated' => (bool) $inscription->certificat_eligible,
            'evaluations_passed' => $this->requiredEvaluationsPassed($inscription),
        ];

        return [
            'eligible' => ! in_array(false, $checks, true),
            'checks' => $checks,
        ];
    }

    public function isEligible(Inscription $inscription): bool
    {
        return $this->check($inscription)['eligible'];
    }

    private function requiredEvaluationsPassed(Inscription $inscription): bool
    {
        $requiredEvaluations = $inscription->cours->evaluations()
            ->where('actif', true)
            ->pluck('id');

        if ($requiredEvaluations->isEmpty()) {
            return true;
        }

        $passedEvaluations = $inscription->candidat->soumissionsEvaluation()
            ->whereIn('evaluation_id', $requiredEvaluations)
            ->where('reussi', true)
            ->whereNotNull('date_soumission')
            ->distinct()
            ->pluck('evaluation_id');

        return $requiredEvaluations->diff($passedEvaluations)->isEmpty();
    }
}

