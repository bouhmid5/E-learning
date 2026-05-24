<?php

namespace App\Services\Courses;

use App\Enums\StatutCours;
use App\Models\Cours;
use DomainException;

class CourseWorkflowService
{
    public function submitForValidation(Cours $cours): Cours
    {
        if (! $cours->lecons()->exists()) {
            throw new DomainException('Un cours doit contenir au moins une leçon avant soumission.');
        }

        $cours->forceFill([
            'statut' => StatutCours::EN_ATTENTE_VALIDATION,
            'motif_rejet' => null,
        ])->save();

        return $cours;
    }
}

