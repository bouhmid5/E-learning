<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Inscription;
use App\Models\Ressource;
use App\Services\Enrollment\EnrollmentService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function enroll(Cours $cours, EnrollmentService $service): RedirectResponse
    {
        try {
            $inscription = $service->enroll(Auth::user()->candidat, $cours);
        } catch (DomainException $exception) {
            return back()->withErrors(['enrollment' => $exception->getMessage()]);
        }

        return redirect()->route('candidate.enrollments.show', $inscription);
    }

    public function index(): View
    {
        return view('candidate.enrollments.index', [
            'inscriptions' => Auth::user()->candidat
                ->inscriptions()
                ->with('cours.categorie')
                ->latest()
                ->paginate(12),
        ]);
    }

    public function show(Inscription $inscription): View
    {
        Gate::authorize('view', $inscription);

        $inscription->load(['cours.categorie', 'cours.formateur.utilisateur']);

        return view('candidate.enrollments.show', [
            'inscription' => $inscription,
        ]);
    }

    public function lessons(Inscription $inscription): View
    {
        Gate::authorize('viewLessons', $inscription);

        $inscription->load(['cours.lecons.ressources', 'progressionsLecons']);

        return view('candidate.enrollments.lessons', [
            'inscription' => $inscription,
        ]);
    }

    public function download(Inscription $inscription, Ressource $ressource): RedirectResponse
    {
        Gate::authorize('downloadResource', [$inscription, $ressource]);

        return redirect()->away($ressource->url);
    }
}
