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
use DomainException;
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
        $this->ensureTrainerIsPending($formateur);

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
        $this->ensureTrainerIsPending($formateur);

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
        $this->ensureJustificatifIsPending($justificatif);

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
        $this->ensureJustificatifIsPending($justificatif);

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
        $this->ensureCourseIsPendingValidation($cours);

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
        $this->ensureCourseIsPendingValidation($cours);

        $cours->forceFill([
            'administrateur_validateur_id' => $admin->id,
            'statut' => StatutCours::REJETE,
            'date_publication' => null,
            'motif_rejet' => $reason,
        ])->save();

        return $cours;
    }

    private function ensureTrainerIsPending(Formateur $formateur): void
    {
        if ($formateur->statut_validation !== StatutCompte::EN_ATTENTE) {
            throw new DomainException('Seuls les formateurs en attente peuvent etre valides ou rejetes.');
        }
    }

    private function ensureJustificatifIsPending(JustificatifFormateur $justificatif): void
    {
        if ($justificatif->statut !== StatutJustificatif::EN_ATTENTE) {
            throw new DomainException('Seuls les justificatifs en attente peuvent etre valides ou rejetes.');
        }
    }

    private function ensureCourseIsPendingValidation(Cours $cours): void
    {
        if ($cours->statut !== StatutCours::EN_ATTENTE_VALIDATION) {
            throw new DomainException('Seuls les cours en attente de validation peuvent etre valides ou rejetes.');
        }
    }
}
