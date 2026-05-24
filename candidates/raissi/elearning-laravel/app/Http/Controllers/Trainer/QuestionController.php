<?php

namespace App\Http\Controllers\Trainer;

use App\Enums\TypeQuestion;
use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    public function store(Request $request, Evaluation $evaluation): RedirectResponse
    {
        Gate::authorize('manage', $evaluation);

        $validated = $this->validatedQuestion($request);
        $question = $evaluation->questions()->create($validated['question']);

        $this->syncCriterion($question, $validated['criterion']);

        return redirect()->route('trainer.evaluations.edit', $evaluation);
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        Gate::authorize('manage', $question->evaluation);

        $validated = $this->validatedQuestion($request);
        $question->update($validated['question']);

        $this->syncCriterion($question, $validated['criterion']);

        return redirect()->route('trainer.evaluations.edit', $question->evaluation);
    }

    public function destroy(Question $question): RedirectResponse
    {
        Gate::authorize('manage', $question->evaluation);

        $evaluation = $question->evaluation;
        $question->delete();

        return redirect()->route('trainer.evaluations.edit', $evaluation);
    }

    private function validatedQuestion(Request $request): array
    {
        $validated = $request->validate([
            'enonce' => ['required', 'string'],
            'type' => ['required', Rule::in(array_map(fn (TypeQuestion $type) => $type->value, TypeQuestion::cases()))],
            'points' => ['required', 'numeric', 'min:0'],
            'critere_description' => ['nullable', 'string'],
            'valeur_attendue' => ['nullable', 'string'],
            'tolerance' => ['nullable', 'numeric', 'min:0'],
        ]);

        return [
            'question' => [
                'enonce' => $validated['enonce'],
                'type' => $validated['type'],
                'points' => $validated['points'],
            ],
            'criterion' => [
                'description' => $validated['critere_description'] ?? null,
                'valeur_attendue' => $validated['valeur_attendue'] ?? null,
                'tolerance' => $validated['tolerance'] ?? null,
            ],
        ];
    }

    private function syncCriterion(Question $question, array $criterion): void
    {
        if (! $criterion['description'] && ! $criterion['valeur_attendue'] && $criterion['tolerance'] === null) {
            return;
        }

        $question->criteresCorrection()->updateOrCreate(
            ['evaluation_id' => $question->evaluation_id],
            [
                'description' => $criterion['description'] ?? 'Critère automatique',
                'valeur_attendue' => $criterion['valeur_attendue'],
                'tolerance' => $criterion['tolerance'],
                'poids' => 1,
            ]
        );
    }
}

