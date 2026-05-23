<?php

namespace App\Http\Controllers\Public;

use App\Enums\StatutCours;
use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('categories.index', [
            'categories' => Categorie::query()
                ->withCount(['cours' => fn ($query) => $query->where('statut', StatutCours::PUBLIE->value)])
                ->orderBy('nom')
                ->get(),
        ]);
    }

    public function courses(Categorie $categorie): View
    {
        $courses = $categorie->cours()
            ->with(['categorie', 'formateur.utilisateur'])
            ->where('statut', StatutCours::PUBLIE->value)
            ->orderByDesc('date_publication')
            ->paginate(12)
            ->withQueryString();

        return view('courses.index', [
            'courses' => $courses,
            'categories' => Categorie::query()->orderBy('nom')->get(),
            'formateurs' => collect(),
            'filters' => ['category' => $categorie->id],
            'currentCategory' => $categorie,
        ]);
    }
}
