<?php

namespace App\Providers;

use App\Models\Administrateur;
use App\Models\Utilisateur;
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
