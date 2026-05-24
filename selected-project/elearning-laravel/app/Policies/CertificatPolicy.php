<?php

namespace App\Policies;

use App\Models\Certificat;
use App\Models\Utilisateur;

class CertificatPolicy
{
    public function view(Utilisateur $utilisateur, Certificat $certificat): bool
    {
        return $utilisateur->candidat()->exists()
            && $certificat->inscription?->candidat_id === $utilisateur->candidat->id;
    }

    public function download(Utilisateur $utilisateur, Certificat $certificat): bool
    {
        return $this->view($utilisateur, $certificat)
            && $certificat->actif;
    }
}

