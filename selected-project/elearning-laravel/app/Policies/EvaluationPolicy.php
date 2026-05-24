<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\Utilisateur;

class EvaluationPolicy
{
    public function manage(Utilisateur $utilisateur, Evaluation $evaluation): bool
    {
        return $utilisateur->formateur()->exists()
            && $evaluation->cours->formateur_id === $utilisateur->formateur->id;
    }

    public function createForCourse(Utilisateur $utilisateur, string $coursFormateurId): bool
    {
        return $utilisateur->formateur()->exists()
            && $coursFormateurId === $utilisateur->formateur->id;
    }

    public function submit(Utilisateur $utilisateur, Evaluation $evaluation): bool
    {
        return $utilisateur->candidat()->exists()
            && $utilisateur->candidat->inscriptions()
                ->where('cours_id', $evaluation->cours_id)
                ->exists();
    }
}

