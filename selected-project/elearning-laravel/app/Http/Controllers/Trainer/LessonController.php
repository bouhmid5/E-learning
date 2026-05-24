<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\StoreLessonRequest;
use App\Http\Requests\Trainer\UpdateLessonRequest;
use App\Models\Cours;
use App\Models\Lecon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class LessonController extends Controller
{
    public function store(StoreLessonRequest $request, Cours $cours): RedirectResponse
    {
        Gate::authorize('update', $cours);

        $cours->lecons()->create($request->validated());

        return redirect()->route('trainer.courses.show', $cours);
    }

    public function update(UpdateLessonRequest $request, Lecon $lecon): RedirectResponse
    {
        Gate::authorize('update', $lecon->cours);

        $lecon->update($request->validated());

        return redirect()->route('trainer.courses.show', $lecon->cours);
    }

    public function destroy(Lecon $lecon): RedirectResponse
    {
        Gate::authorize('update', $lecon->cours);

        $cours = $lecon->cours;
        $lecon->delete();

        return redirect()->route('trainer.courses.show', $cours);
    }
}

