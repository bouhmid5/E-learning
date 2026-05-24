<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatutCours;
use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Services\Admin\AdminValidationService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseValidationController extends Controller
{
    public function pending(): View
    {
        return view('admin.courses.pending', [
            'courses' => Cours::query()
                ->with(['categorie', 'formateur.utilisateur'])
                ->where('statut', StatutCours::EN_ATTENTE_VALIDATION->value)
                ->latest()
                ->paginate(20),
        ]);
    }

    public function validateCourse(Cours $cours, AdminValidationService $service): RedirectResponse
    {
        try {
            $service->validateCourse($cours, Auth::guard('admin')->user());
        } catch (DomainException $exception) {
            return back()->withErrors(['validation' => $exception->getMessage()]);
        }

        return back()->with('status', 'Cours publie.');
    }

    public function rejectCourse(Request $request, Cours $cours, AdminValidationService $service): RedirectResponse
    {
        $validated = $request->validate([
            'motif_rejet' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $service->rejectCourse($cours, Auth::guard('admin')->user(), $validated['motif_rejet']);
        } catch (DomainException $exception) {
            return back()->withErrors(['validation' => $exception->getMessage()]);
        }

        return back()->with('status', 'Cours rejete.');
    }
}
