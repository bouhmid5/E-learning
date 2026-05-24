<?php

namespace App\Http\Controllers\Candidate;

use App\Enums\StatutSoumission;
use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\SoumissionEvaluation;
use App\Services\Evaluations\AutomaticCorrectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class EvaluationSubmissionController extends Controller
{
    public function show(Evaluation $evaluation): View
    {
        Gate::authorize('submit', $evaluation);

        $evaluation->load(['cours', 'questions.optionsReponse']);

        return view('candidate.evaluations.show', [
            'evaluation' => $evaluation,
        ]);
    }

    public function start(Evaluation $evaluation): RedirectResponse
    {
        Gate::authorize('submit', $evaluation);

        $candidat = Auth::user()->candidat;
        $attempt = ((int) SoumissionEvaluation::query()
            ->where('candidat_id', $candidat->id)
            ->where('evaluation_id', $evaluation->id)
            ->max('numero_tentative')) + 1;

        $soumission = SoumissionEvaluation::create([
            'candidat_id' => $candidat->id,
            'evaluation_id' => $evaluation->id,
            'date_debut' => now(),
            'numero_tentative' => $attempt,
            'statut' => StatutSoumission::SOUMISE,
        ]);

        return redirect()->route('candidate.submissions.show', $soumission);
    }

    public function submit(Request $request, Evaluation $evaluation, AutomaticCorrectionService $correction): RedirectResponse
    {
        Gate::authorize('submit', $evaluation);

        $validated = $request->validate([
            'soumission_id' => ['nullable', 'uuid', 'exists:soumission_evaluations,id'],
            'answers' => ['required', 'array'],
        ]);

        $soumission = $this->submissionFor($evaluation, $validated['soumission_id'] ?? null);
        $correction->submit($soumission, $validated['answers']);

        return redirect()->route('candidate.submissions.show', $soumission);
    }

    public function submission(SoumissionEvaluation $soumission): View
    {
        abort_unless($soumission->candidat_id === Auth::user()->candidat->id, 403);

        $soumission->load(['evaluation.cours', 'reponsesCandidats.question']);

        return view('candidate.submissions.show', [
            'soumission' => $soumission,
        ]);
    }

    public function results(): View
    {
        $soumissions = SoumissionEvaluation::query()
            ->with('evaluation.cours')
            ->where('candidat_id', Auth::user()->candidat->id)
            ->whereNotNull('date_soumission')
            ->latest('date_soumission')
            ->paginate(12);

        return view('candidate.evaluations.results', [
            'soumissions' => $soumissions,
        ]);
    }

    private function submissionFor(Evaluation $evaluation, ?string $soumissionId): SoumissionEvaluation
    {
        $query = SoumissionEvaluation::query()
            ->where('candidat_id', Auth::user()->candidat->id)
            ->where('evaluation_id', $evaluation->id);

        if ($soumissionId) {
            return $query->where('id', $soumissionId)->firstOrFail();
        }

        $attempt = ((int) (clone $query)->max('numero_tentative')) + 1;

        return (clone $query)->whereNull('date_soumission')->latest()->first()
            ?? SoumissionEvaluation::create([
                'candidat_id' => Auth::user()->candidat->id,
                'evaluation_id' => $evaluation->id,
                'date_debut' => now(),
                'numero_tentative' => $attempt,
                'statut' => StatutSoumission::SOUMISE,
            ]);
    }
}
