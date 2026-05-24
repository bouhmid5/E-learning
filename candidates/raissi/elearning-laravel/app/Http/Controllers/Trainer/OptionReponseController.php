<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\OptionReponse;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OptionReponseController extends Controller
{
    public function store(Request $request, Question $question): RedirectResponse
    {
        Gate::authorize('manage', $question->evaluation);

        $question->optionsReponse()->create($this->validatedOption($request));

        return redirect()->route('trainer.evaluations.edit', $question->evaluation);
    }

    public function update(Request $request, OptionReponse $option): RedirectResponse
    {
        Gate::authorize('manage', $option->question->evaluation);

        $option->update($this->validatedOption($request));

        return redirect()->route('trainer.evaluations.edit', $option->question->evaluation);
    }

    public function destroy(OptionReponse $option): RedirectResponse
    {
        Gate::authorize('manage', $option->question->evaluation);

        $evaluation = $option->question->evaluation;
        $option->delete();

        return redirect()->route('trainer.evaluations.edit', $evaluation);
    }

    private function validatedOption(Request $request): array
    {
        return $request->validate([
            'texte' => ['required', 'string'],
            'est_correcte' => ['nullable', 'boolean'],
        ]) + ['est_correcte' => false];
    }
}

