<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\Lecon;
use App\Services\Enrollment\ProgressionService;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class ProgressionController extends Controller
{
    public function complete(Inscription $inscription, Lecon $lecon, ProgressionService $service): RedirectResponse
    {
        Gate::authorize('completeLesson', [$inscription, $lecon]);

        try {
            $service->completeLesson($inscription, $lecon);
        } catch (DomainException $exception) {
            return back()->withErrors(['progression' => $exception->getMessage()]);
        }

        return back()->with('status', 'Lecon marquee comme terminee.');
    }

    public function progress(Inscription $inscription, ProgressionService $service): JsonResponse
    {
        Gate::authorize('view', $inscription);

        return response()->json([
            'progression' => $service->refreshInscriptionProgress($inscription),
            'statut' => $inscription->fresh()->statut->value,
        ]);
    }
}
