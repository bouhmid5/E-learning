<?php

namespace App\Services\Enrollment;

use App\Enums\StatutCours;
use App\Enums\StatutInscription;
use App\Models\Candidat;
use App\Models\Cours;
use App\Models\Inscription;
use DomainException;

class EnrollmentService
{
    public function enroll(Candidat $candidat, Cours $cours): Inscription
    {
        if ($cours->statut !== StatutCours::PUBLIE) {
            throw new DomainException('Inscription possible uniquement pour un cours publie.');
        }

        if (Inscription::query()->where('candidat_id', $candidat->id)->where('cours_id', $cours->id)->exists()) {
            throw new DomainException('Vous etes deja inscrit a ce cours.');
        }

        return Inscription::create([
            'candidat_id' => $candidat->id,
            'cours_id' => $cours->id,
            'date_inscription' => now(),
            'progression' => 0,
            'statut' => StatutInscription::EN_COURS,
            'date_fin' => null,
            'certificat_eligible' => false,
        ]);
    }
}
