<?php

namespace App\Providers;

use App\Enums\StatutCompte;
use App\Models\Administrateur;
use App\Models\Certificat;
use App\Models\Cours;
use App\Models\Evaluation;
use App\Models\Inscription;
use App\Models\Utilisateur;
use App\Policies\CoursePolicy;
use App\Policies\CertificatPolicy;
use App\Policies\EvaluationPolicy;
use App\Policies\InscriptionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Cours::class, CoursePolicy::class);
        Gate::policy(Certificat::class, CertificatPolicy::class);
        Gate::policy(Evaluation::class, EvaluationPolicy::class);
        Gate::policy(Inscription::class, InscriptionPolicy::class);

        Gate::define('access-role', function (Administrateur|Utilisateur $user, string $role): bool {
            return match ($role) {
                'admin' => $user instanceof Administrateur
                    && $user->statut === StatutCompte::ACTIF,
                'candidat' => $user instanceof Utilisateur
                    && $user->statut === StatutCompte::ACTIF
                    && $user->candidat()->exists(),
                'formateur' => $user instanceof Utilisateur
                    && $user->statut === StatutCompte::ACTIF
                    && $user->formateur?->statut_validation === StatutCompte::ACTIF,
                default => false,
            };
        });
    }
}
