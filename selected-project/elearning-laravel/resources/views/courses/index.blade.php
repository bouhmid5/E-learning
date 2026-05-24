@extends('layouts.app')

@section('title', 'Catalogue des cours')

@section('content')
    <section class="page-heading split-heading">
        <div>
            <p class="eyebrow">Catalogue public</p>
            <h1>{{ isset($currentCategory) ? 'Cours: '.$currentCategory->nom : 'Catalogue des cours' }}</h1>
            <p>Parcourez les cours publies et filtrez selon votre objectif d'apprentissage.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="button-link">Voir les categories</a>
    </section>

    <form method="GET" action="{{ route('courses.index') }}" class="filter-panel">
        <div class="filter-row filter-row-wide">
            <label>
                Recherche
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Titre ou description">
            </label>
            <label>
                Mot-cle
                <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Formateur, niveau, langue">
            </label>
        </div>

        <div class="filter-row">
            <label>
                Categorie
                <select name="category">
                    <option value="">Toutes</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie->id }}" @selected(($filters['category'] ?? '') === $categorie->id)>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label>
                Niveau
                <input type="text" name="niveau" value="{{ $filters['niveau'] ?? '' }}" placeholder="Debutant">
            </label>

            <label>
                Langue
                <input type="text" name="langue" value="{{ $filters['langue'] ?? '' }}" placeholder="fr">
            </label>

            <label>
                Formateur
                <select name="trainer">
                    <option value="">Tous</option>
                    @foreach ($formateurs as $formateur)
                        <option value="{{ $formateur->id }}" @selected(($filters['trainer'] ?? '') === $formateur->id)>
                            {{ $formateur->utilisateur?->prenom }} {{ $formateur->utilisateur?->nom }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="filter-row">
            <label>
                Prix min
                <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" min="0" step="0.01">
            </label>
            <label>
                Prix max
                <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" min="0" step="0.01">
            </label>
            <label>
                Duree min
                <input type="number" name="min_duration" value="{{ $filters['min_duration'] ?? '' }}" min="0">
            </label>
            <label>
                Duree max
                <input type="number" name="max_duration" value="{{ $filters['max_duration'] ?? '' }}" min="0">
            </label>
        </div>

        <div class="filter-actions">
            <label>
                Trier par
                <select name="sort">
                    @foreach (['date_publication' => 'Publication', 'titre' => 'Titre', 'prix' => 'Prix', 'duree_estimee' => 'Duree'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['sort'] ?? 'date_publication') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>

            <label>
                Sens
                <select name="direction">
                    <option value="desc" @selected(($filters['direction'] ?? 'desc') === 'desc')>Descendant</option>
                    <option value="asc" @selected(($filters['direction'] ?? '') === 'asc')>Ascendant</option>
                </select>
            </label>

            <button type="submit">Appliquer</button>
            <a href="{{ route('courses.index') }}">Reinitialiser</a>
        </div>
    </form>

    @if ($courses->isEmpty())
        <section class="empty-state">
            <h2>Aucun cours publie trouve</h2>
            <p>Essayez d'elargir votre recherche ou de retirer certains filtres.</p>
        </section>
    @else
        <section class="course-grid">
            @foreach ($courses as $cours)
                <article class="course-card">
                    <p class="eyebrow">{{ $cours->categorie?->nom ?? 'Sans categorie' }}</p>
                    <h2><a href="{{ route('courses.show', $cours) }}">{{ $cours->titre }}</a></h2>
                    <p>{{ \Illuminate\Support\Str::limit($cours->description, 140) }}</p>
                    <dl class="meta-grid">
                        <div><dt>Niveau</dt><dd>{{ $cours->niveau }}</dd></div>
                        <div><dt>Langue</dt><dd>{{ $cours->langue }}</dd></div>
                        <div><dt>Prix</dt><dd>{{ number_format((float) $cours->prix, 2) }} EUR</dd></div>
                        <div><dt>Duree</dt><dd>{{ $cours->duree_estimee }} min</dd></div>
                    </dl>
                    <a class="card-action" href="{{ route('courses.show', $cours) }}">Voir le cours</a>
                </article>
            @endforeach
        </section>

        {{ $courses->links() }}
    @endif
@endsection
