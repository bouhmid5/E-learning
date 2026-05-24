<?php

namespace App\Policies;

use App\Enums\StatutCours;
use App\Models\Cours;
use App\Models\Utilisateur;

class CoursePolicy
{
    public function create(Utilisateur $utilisateur): bool
    {
        return $utilisateur->formateur()->exists();
    }

    public function viewTrainer(Utilisateur $utilisateur, Cours $cours): bool
    {
        return $this->ownsCourse($utilisateur, $cours);
    }

    public function update(Utilisateur $utilisateur, Cours $cours): bool
    {
        return $this->ownsCourse($utilisateur, $cours)
            && in_array($cours->statut, [StatutCours::BROUILLON, StatutCours::REJETE], true);
    }

    public function delete(Utilisateur $utilisateur, Cours $cours): bool
    {
        return $this->update($utilisateur, $cours);
    }

    public function submit(Utilisateur $utilisateur, Cours $cours): bool
    {
        return $this->update($utilisateur, $cours);
    }

    private function ownsCourse(Utilisateur $utilisateur, Cours $cours): bool
    {
        return $utilisateur->formateur()->exists()
            && $cours->formateur_id === $utilisateur->formateur->id;
    }
}

