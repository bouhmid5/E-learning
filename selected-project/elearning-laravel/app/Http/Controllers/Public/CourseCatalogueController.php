<?php

namespace App\Http\Controllers\Public;

use App\Enums\StatutCours;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catalogue\CourseFilterRequest;
use App\Models\Categorie;
use App\Models\Cours;
use App\Models\Formateur;
use Illuminate\Contracts\View\View;

class CourseCatalogueController extends Controller
{
    public function index(CourseFilterRequest $request): View
    {
        $filters = $request->filters();
        $sort = $filters['sort'] ?? 'date_publication';
        $direction = $filters['direction'] ?? 'desc';

        $courses = Cours::query()
            ->with(['categorie', 'formateur.utilisateur'])
            ->where('statut', StatutCours::PUBLIE->value)
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('titre', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, fn ($query, string $category) => $query->where('categorie_id', $category))
            ->when($filters['niveau'] ?? null, fn ($query, string $niveau) => $query->where('niveau', $niveau))
            ->when($filters['langue'] ?? null, fn ($query, string $langue) => $query->where('langue', $langue))
            ->when($filters['min_price'] ?? null, fn ($query, mixed $price) => $query->where('prix', '>=', $price))
            ->when($filters['max_price'] ?? null, fn ($query, mixed $price) => $query->where('prix', '<=', $price))
            ->when($filters['min_duration'] ?? null, fn ($query, mixed $duration) => $query->where('duree_estimee', '>=', $duration))
            ->when($filters['max_duration'] ?? null, fn ($query, mixed $duration) => $query->where('duree_estimee', '<=', $duration))
            ->when($filters['trainer'] ?? null, fn ($query, string $trainer) => $query->where('formateur_id', $trainer))
            ->when($filters['keyword'] ?? null, function ($query, string $keyword): void {
                $query->where(function ($query) use ($keyword): void {
                    $query->where('titre', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('niveau', 'like', "%{$keyword}%")
                        ->orWhere('langue', 'like', "%{$keyword}%")
                        ->orWhereHas('formateur.utilisateur', function ($query) use ($keyword): void {
                            $query->where('nom', 'like', "%{$keyword}%")
                                ->orWhere('prenom', 'like', "%{$keyword}%");
                        });
                });
            })
            ->orderBy($sort, $direction)
            ->paginate(12)
            ->withQueryString();

        return view('courses.index', [
            'courses' => $courses,
            'categories' => Categorie::query()->orderBy('nom')->get(),
            'formateurs' => Formateur::query()->with('utilisateur')->get(),
            'filters' => $filters,
        ]);
    }

    public function show(Cours $cours): View
    {
        abort_unless($cours->statut === StatutCours::PUBLIE, 404);

        $cours->load(['categorie', 'formateur.utilisateur', 'lecons.ressources', 'evaluations']);

        return view('courses.show', [
            'cours' => $cours,
        ]);
    }
}
