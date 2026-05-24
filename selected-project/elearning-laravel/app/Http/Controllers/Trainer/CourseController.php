<?php

namespace App\Http\Controllers\Trainer;

use App\Enums\StatutCours;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\StoreCourseRequest;
use App\Http\Requests\Trainer\UpdateCourseRequest;
use App\Models\Categorie;
use App\Models\Cours;
use App\Services\Courses\CourseWorkflowService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $formateur = Auth::user()->formateur;

        return view('trainer.courses.index', [
            'courses' => Cours::query()
                ->with('categorie')
                ->where('formateur_id', $formateur->id)
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Cours::class);

        return view('trainer.courses.create', [
            'categories' => Categorie::query()->orderBy('nom')->get(),
        ]);
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        Gate::authorize('create', Cours::class);

        $cours = Cours::create(array_merge($request->validated(), [
            'formateur_id' => Auth::user()->formateur->id,
            'statut' => StatutCours::BROUILLON,
        ]));

        return redirect()->route('trainer.courses.show', $cours);
    }

    public function show(Cours $cours): View
    {
        Gate::authorize('viewTrainer', $cours);

        $cours->load(['categorie', 'lecons.ressources']);

        return view('trainer.courses.show', [
            'cours' => $cours,
        ]);
    }

    public function edit(Cours $cours): View
    {
        Gate::authorize('update', $cours);

        return view('trainer.courses.edit', [
            'cours' => $cours,
            'categories' => Categorie::query()->orderBy('nom')->get(),
        ]);
    }

    public function update(UpdateCourseRequest $request, Cours $cours): RedirectResponse
    {
        Gate::authorize('update', $cours);

        $cours->update($request->validated());

        return redirect()->route('trainer.courses.show', $cours);
    }

    public function destroy(Cours $cours): RedirectResponse
    {
        Gate::authorize('delete', $cours);

        $cours->delete();

        return redirect()->route('trainer.courses.index');
    }

    public function submit(Cours $cours, CourseWorkflowService $workflow): RedirectResponse
    {
        Gate::authorize('submit', $cours);

        try {
            $workflow->submitForValidation($cours);
        } catch (DomainException $exception) {
            return back()->withErrors(['cours' => $exception->getMessage()]);
        }

        return redirect()->route('trainer.courses.show', $cours);
    }
}

