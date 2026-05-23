<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatutCompte;
use App\Enums\StatutJustificatif;
use App\Http\Controllers\Controller;
use App\Models\Formateur;
use App\Models\JustificatifFormateur;
use App\Services\Admin\AdminValidationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TrainerValidationController extends Controller
{
    public function pending(): View
    {
        return view('admin.trainers.pending', [
            'trainers' => Formateur::query()
                ->with(['utilisateur', 'justificatifs'])
                ->where('statut_validation', StatutCompte::EN_ATTENTE->value)
                ->latest()
                ->paginate(20),
            'justificatifs' => JustificatifFormateur::query()
                ->with('formateur.utilisateur')
                ->where('statut', StatutJustificatif::EN_ATTENTE->value)
                ->latest()
                ->get(),
        ]);
    }

    public function validateTrainer(Formateur $formateur, AdminValidationService $service): RedirectResponse
    {
        $service->validateTrainer($formateur, Auth::guard('admin')->user());

        return back()->with('status', 'Formateur valide.');
    }

    public function rejectTrainer(Request $request, Formateur $formateur, AdminValidationService $service): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $service->rejectTrainer($formateur, Auth::guard('admin')->user(), $validated['reason']);

        return back()->with('status', 'Formateur rejete.');
    }

    public function validateJustificatif(JustificatifFormateur $justificatif, AdminValidationService $service): RedirectResponse
    {
        $service->validateJustificatif($justificatif, Auth::guard('admin')->user());

        return back()->with('status', 'Justificatif valide.');
    }

    public function rejectJustificatif(Request $request, JustificatifFormateur $justificatif, AdminValidationService $service): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $service->rejectJustificatif($justificatif, Auth::guard('admin')->user(), $validated['reason']);

        return back()->with('status', 'Justificatif rejete.');
    }
}
