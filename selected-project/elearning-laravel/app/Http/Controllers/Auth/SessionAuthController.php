<?php

namespace App\Http\Controllers\Auth;

use App\Enums\StatutCompte;
use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\Formateur;
use App\Models\Utilisateur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'account_type' => ['nullable', Rule::in(['utilisateur', 'admin'])],
            'remember' => ['nullable', 'boolean'],
        ]);

        $guard = ($validated['account_type'] ?? 'utilisateur') === 'admin' ? 'admin' : 'web';

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'statut' => StatutCompte::ACTIF->value,
        ];

        if (! Auth::guard($guard)->attempt($credentials, (bool) ($validated['remember'] ?? false))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        if ($guard === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        /** @var Utilisateur $utilisateur */
        $utilisateur = Auth::guard('web')->user();
        $utilisateur->forceFill(['derniere_connexion' => now()])->save();

        return redirect()->intended($this->dashboardRouteFor($utilisateur));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showCandidateRegistration(): View
    {
        return view('auth.register-candidate');
    }

    public function registerCandidate(Request $request): RedirectResponse
    {
        $validated = $this->validateRegistration($request, [
            'niveau' => ['nullable', 'string', 'max:255'],
            'objectif_apprentissage' => ['nullable', 'string'],
        ]);

        $utilisateur = DB::transaction(function () use ($validated): Utilisateur {
            $utilisateur = Utilisateur::create($this->utilisateurPayload($validated));

            Candidat::create([
                'utilisateur_id' => $utilisateur->id,
                'niveau' => $validated['niveau'] ?? null,
                'objectif_apprentissage' => $validated['objectif_apprentissage'] ?? null,
            ]);

            return $utilisateur;
        });

        Auth::guard('web')->login($utilisateur);
        $request->session()->regenerate();

        return redirect()->route('candidate.dashboard');
    }

    public function showTrainerRegistration(): View
    {
        return view('auth.register-trainer');
    }

    public function registerTrainer(Request $request): RedirectResponse
    {
        $validated = $this->validateRegistration($request, [
            'specialite' => ['nullable', 'string', 'max:255'],
            'biographie' => ['nullable', 'string'],
        ]);

        $utilisateur = DB::transaction(function () use ($validated): Utilisateur {
            $utilisateur = Utilisateur::create($this->utilisateurPayload($validated));

            Formateur::create([
                'utilisateur_id' => $utilisateur->id,
                'specialite' => $validated['specialite'] ?? null,
                'biographie' => $validated['biographie'] ?? null,
                'statut_validation' => StatutCompte::EN_ATTENTE,
            ]);

            return $utilisateur;
        });

        Auth::guard('web')->login($utilisateur);
        $request->session()->regenerate();

        return redirect()->route('trainer.dashboard');
    }

    private function validateRegistration(Request $request, array $extraRules): array
    {
        return $request->validate(array_merge([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:utilisateurs,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telephone' => ['nullable', 'string', 'max:255'],
        ], $extraRules));
    }

    private function utilisateurPayload(array $validated): array
    {
        return [
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'mot_de_passe_hash' => Hash::make($validated['password']),
            'telephone' => $validated['telephone'] ?? null,
            'statut' => StatutCompte::ACTIF,
        ];
    }

    private function dashboardRouteFor(Utilisateur $utilisateur): string
    {
        if ($utilisateur->candidat) {
            return route('candidate.dashboard');
        }

        if ($utilisateur->formateur) {
            return route('trainer.dashboard');
        }

        return route('dashboard');
    }
}

