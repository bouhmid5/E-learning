<?php

namespace App\Http\Controllers\Trainer;

use App\Enums\TypeEvaluation;
use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Evaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    public function index(Cours $cours): View
    {
        Gate::authorize('createForCourse', [Evaluation::class, $cours->formateur_id]);

        return view('trainer.evaluations.index', [
            'cours' => $cours,
            'evaluations' => $cours->evaluations()->withCount('questions')->orderBy('ordre')->paginate(12),
        ]);
    }

    public function create(Cours $cours): View
    {
        Gate::authorize('createForCourse', [Evaluation::class, $cours->formateur_id]);

        return view('trainer.evaluations.create', [
            'cours' => $cours,
        ]);
    }

    public function store(Request $request, Cours $cours): RedirectResponse
    {
        Gate::authorize('createForCourse', [Evaluation::class, $cours->formateur_id]);

        $evaluation = $cours->evaluations()->create($this->validatedEvaluation($request));

        return redirect()->route('trainer.evaluations.edit', $evaluation);
    }

    public function edit(Evaluation $evaluation): View
    {
        Gate::authorize('manage', $evaluation);

        $evaluation->load(['cours', 'questions.optionsReponse', 'questions.criteresCorrection']);

        return view('trainer.evaluations.edit', [
            'evaluation' => $evaluation,
        ]);
    }

    public function update(Request $request, Evaluation $evaluation): RedirectResponse
    {
        Gate::authorize('manage', $evaluation);

        $evaluation->update($this->validatedEvaluation($request));

        return redirect()->route('trainer.evaluations.edit', $evaluation);
    }

    public function destroy(Evaluation $evaluation): RedirectResponse
    {
        Gate::authorize('manage', $evaluation);

        $cours = $evaluation->cours;
        $evaluation->delete();

        return redirect()->route('trainer.courses.evaluations.index', $cours);
    }

    private function validatedEvaluation(Request $request): array
    {
        return $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type_evaluation' => ['required', Rule::in(array_map(fn (TypeEvaluation $type) => $type->value, TypeEvaluation::cases()))],
            'score_max' => ['required', 'numeric', 'min:0'],
            'seuil_reussite' => ['required', 'numeric', 'min:0'],
            'ordre' => ['required', 'integer', 'min:1'],
            'actif' => ['nullable', 'boolean'],
        ]) + ['actif' => false];
    }
}

