<?php

namespace App\Services\Admin;

use App\Enums\StatutCompte;
use App\Enums\StatutCours;
use App\Enums\StatutJustificatif;
use App\Models\Administrateur;
use App\Models\Cours;
use App\Models\Formateur;
use App\Models\JustificatifFormateur;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\DB;

class AdminValidationService
{
    public function updateUserStatus(Utilisateur $utilisateur, StatutCompte $statut): Utilisateur
    {
        $utilisateur->forceFill(['statut' => $statut])->save();

        return $utilisateur;
    }

    public function validateTrainer(Formateur $formateur, Administrateur $admin): Formateur
    {
        return DB::transaction(function () use ($formateur, $admin): Formateur {
            $formateur->forceFill([
                'administrateur_validateur_id' => $admin->id,
                'statut_validation' => StatutCompte::ACTIF,
            ])->save();

            $formateur->utilisateur?->forceFill(['statut' => StatutCompte::ACTIF])->save();

            return $formateur;
        });
    }

    public function rejectTrainer(Formateur $formateur, Administrateur $admin, string $reason): Formateur
    {
        return DB::transaction(function () use ($formateur, $admin, $reason): Formateur {
            $formateur->forceFill([
                'administrateur_validateur_id' => $admin->id,
                'statut_validation' => StatutCompte::REJETE,
            ])->save();

            $formateur->utilisateur?->forceFill(['statut' => StatutCompte::REJETE])->save();

            $formateur->justificatifs()->where('statut', StatutJustificatif::EN_ATTENTE->value)->update([
                'administrateur_validateur_id' => $admin->id,
                'statut' => StatutJustificatif::REJETE->value,
                'date_validation' => now(),
                'commentaire_validation' => $reason,
            ]);

            return $formateur;
        });
    }

    public function validateJustificatif(JustificatifFormateur $justificatif, Administrateur $admin): JustificatifFormateur
    {
        $justificatif->forceFill([
            'administrateur_validateur_id' => $admin->id,
            'statut' => StatutJustificatif::VALIDE,
            'date_validation' => now(),
            'commentaire_validation' => null,
        ])->save();

        return $justificatif;
    }

    public function rejectJustificatif(JustificatifFormateur $justificatif, Administrateur $admin, string $reason): JustificatifFormateur
    {
        $justificatif->forceFill([
            'administrateur_validateur_id' => $admin->id,
            'statut' => StatutJustificatif::REJETE,
            'date_validation' => now(),
            'commentaire_validation' => $reason,
        ])->save();

        return $justificatif;
    }

    public function validateCourse(Cours $cours, Administrateur $admin): Cours
    {
        $cours->forceFill([
            'administrateur_validateur_id' => $admin->id,
            'statut' => StatutCours::PUBLIE,
            'date_publication' => now(),
            'motif_rejet' => null,
        ])->save();

        return $cours;
    }

    public function rejectCourse(Cours $cours, Administrateur $admin, string $reason): Cours
    {
        $cours->forceFill([
            'administrateur_validateur_id' => $admin->id,
            'statut' => StatutCours::REJETE,
            'date_publication' => null,
            'motif_rejet' => $reason,
        ])->save();

        return $cours;
    }
}
