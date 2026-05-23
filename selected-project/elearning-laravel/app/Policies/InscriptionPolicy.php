<?php

namespace App\Policies;

use App\Models\Inscription;
use App\Models\Lecon;
use App\Models\Ressource;
use App\Models\Utilisateur;

class InscriptionPolicy
{
    public function view(Utilisateur $utilisateur, Inscription $inscription): bool
    {
        return $utilisateur->candidat()->exists()
            && $inscription->candidat_id === $utilisateur->candidat->id;
    }

    public function viewLessons(Utilisateur $utilisateur, Inscription $inscription): bool
    {
        return $this->view($utilisateur, $inscription);
    }

    public function completeLesson(Utilisateur $utilisateur, Inscription $inscription, Lecon $lecon): bool
    {
        return $this->view($utilisateur, $inscription)
            && $lecon->cours_id === $inscription->cours_id;
    }

    public function downloadResource(Utilisateur $utilisateur, Inscription $inscription, Ressource $ressource): bool
    {
        return $this->view($utilisateur, $inscription)
            && $ressource->lecon?->cours_id === $inscription->cours_id
            && $ressource->telechargeable;
    }
}
