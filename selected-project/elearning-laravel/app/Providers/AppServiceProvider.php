<?php

namespace App\Providers;

use App\Models\Administrateur;
use App\Models\Cours;
use App\Models\Utilisateur;
use App\Policies\CoursePolicy;
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

        Gate::define('access-role', function (Administrateur|Utilisateur $user, string $role): bool {
            return match ($role) {
                'admin' => $user instanceof Administrateur,
                'candidat' => $user instanceof Utilisateur && $user->candidat()->exists(),
                'formateur' => $user instanceof Utilisateur && $user->formateur()->exists(),
                default => false,
            };
        });
    }
}
