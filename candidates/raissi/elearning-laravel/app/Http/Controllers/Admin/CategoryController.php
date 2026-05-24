<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Categorie::query()
                ->with('parent')
                ->orderBy('nom')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'categorie' => new Categorie(),
            'parents' => Categorie::query()->orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Categorie::create($this->validated($request));

        return redirect()->route('admin.categories.index')->with('status', 'Categorie creee.');
    }

    public function edit(Categorie $categorie): View
    {
        return view('admin.categories.edit', [
            'categorie' => $categorie,
            'parents' => Categorie::query()
                ->whereKeyNot($categorie->id)
                ->orderBy('nom')
                ->get(),
        ]);
    }

    public function update(Request $request, Categorie $categorie): RedirectResponse
    {
        $categorie->update($this->validated($request, $categorie));

        return redirect()->route('admin.categories.index')->with('status', 'Categorie mise a jour.');
    }

    public function destroy(Categorie $categorie): RedirectResponse
    {
        $categorie->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Categorie supprimee.');
    }

    private function validated(Request $request, ?Categorie $categorie = null): array
    {
        return $request->validate([
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id'),
                Rule::notIn(array_filter([$categorie?->id])),
            ],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
