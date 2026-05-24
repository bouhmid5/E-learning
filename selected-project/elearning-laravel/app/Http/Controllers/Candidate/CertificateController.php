<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Certificat;
use App\Models\Inscription;
use App\Services\Certificates\CertificateEligibilityService;
use App\Services\Certificates\CertificateGenerationService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends Controller
{
    public function eligibility(Inscription $inscription, CertificateEligibilityService $eligibility): View
    {
        Gate::authorize('view', $inscription);

        return view('candidate.certificates.eligibility', [
            'inscription' => $inscription->load('cours'),
            'result' => $eligibility->check($inscription),
        ]);
    }

    public function generate(Inscription $inscription, CertificateGenerationService $generator): RedirectResponse
    {
        Gate::authorize('view', $inscription);

        try {
            $certificat = $generator->generate($inscription);
        } catch (DomainException $exception) {
            return back()->withErrors(['certificate' => $exception->getMessage()]);
        }

        return redirect()->route('candidate.certificates.index')
            ->with('status', "Certificat {$certificat->code_verification} disponible.");
    }

    public function index(): View
    {
        $certificats = Certificat::query()
            ->with('inscription.cours')
            ->whereHas('inscription', fn ($query) => $query->where('candidat_id', Auth::user()->candidat->id))
            ->latest('date_generation')
            ->paginate(12);

        return view('candidate.certificates.index', [
            'certificats' => $certificats,
        ]);
    }

    public function download(Certificat $certificat): StreamedResponse
    {
        Gate::authorize('download', $certificat);

        abort_unless($certificat->fichier_url && Storage::disk('public')->exists($certificat->fichier_url), 404);

        return Storage::disk('public')->download(
            $certificat->fichier_url,
            "certificat-{$certificat->code_verification}.txt"
        );
    }
}

