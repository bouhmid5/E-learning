<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\StatutCompte;
use App\Enums\StatutCours;
use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Cours;
use App\Models\Formateur;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function general(): View
    {
        return view('dashboard.general');
    }

    public function candidate(): View
    {
        $candidat = Auth::user()->candidat;

        $inscriptions = $candidat
            ? $candidat->inscriptions()->with('cours.categorie')->latest()->take(3)->get()
            : collect();

        return view('dashboard.candidate', [
            'inscriptions' => $inscriptions,
            'inscriptionsCount' => $candidat ? $candidat->inscriptions()->count() : 0,
            'certificatesCount' => $candidat
                ? $candidat->inscriptions()->whereHas('certificat')->count()
                : 0,
        ]);
    }

    public function trainer(): View
    {
        $formateur = Auth::user()->formateur;
        $coursesQuery = fn () => $formateur
            ? $formateur->cours()
            : Cours::query()->whereRaw('1 = 0');

        return view('dashboard.trainer', [
            'draftCount' => $coursesQuery()->where('statut', StatutCours::BROUILLON->value)->count(),
            'pendingCount' => $coursesQuery()->where('statut', StatutCours::EN_ATTENTE_VALIDATION->value)->count(),
            'publishedCount' => $coursesQuery()->where('statut', StatutCours::PUBLIE->value)->count(),
            'rejectedCount' => $coursesQuery()->where('statut', StatutCours::REJETE->value)->count(),
            'recentCourses' => $coursesQuery()->with('categorie')->latest()->take(5)->get(),
        ]);
    }

    public function admin(): View
    {
        return view('dashboard.admin', [
            'pendingTrainersCount' => Formateur::query()->where('statut_validation', StatutCompte::EN_ATTENTE->value)->count(),
            'pendingCoursesCount' => Cours::query()->where('statut', StatutCours::EN_ATTENTE_VALIDATION->value)->count(),
            'usersCount' => Utilisateur::query()->count(),
            'categoriesCount' => Categorie::query()->count(),
            'pendingCourses' => Cours::query()
                ->with(['formateur.utilisateur', 'categorie'])
                ->where('statut', StatutCours::EN_ATTENTE_VALIDATION->value)
                ->latest()
                ->take(5)
                ->get(),
            'pendingTrainers' => Formateur::query()
                ->with('utilisateur')
                ->where('statut_validation', StatutCompte::EN_ATTENTE->value)
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
