@extends('layouts.app')

@section('title', 'Catalogue')

@section('content')
    <section class="page-heading split-heading">
        <div>
            <p class="eyebrow">Catalogue Formini</p>
            <h1>{{ isset($currentCategory) ? 'Cours: '.$currentCategory->nom : 'Catalogue des cours' }}</h1>
            <p>Parcourez uniquement les cours publies et affinez votre recherche selon votre objectif.</p>
        </div>
        <a href="{{ route('categories.index') }}" class="button-link button-link-soft">Voir les categories</a>
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
                            {{ trim(($formateur->utilisateur?->prenom ?? '').' '.($formateur->utilisateur?->nom ?? '')) ?: 'Formateur' }}
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

            <button type="submit" data-loading-text="Recherche...">Appliquer</button>
            <a href="{{ route('courses.index') }}" class="button-link button-link-soft">Reinitialiser</a>
        </div>
    </form>

    @if ($courses->isEmpty())
        <x-empty-state title="Aucun cours publie trouve">
            Essayez d'elargir votre recherche ou de retirer certains filtres.
        </x-empty-state>
    @else
        <section class="course-grid" aria-label="Cours publies">
            @foreach ($courses as $cours)
                <article class="course-card">
                    <div class="course-card__body">
                        <div class="card-header-line">
                            <p class="eyebrow">{{ $cours->categorie?->nom ?? 'Sans categorie' }}</p>
                            <x-status-badge>{{ $cours->niveau }}</x-status-badge>
                        </div>
                        <h2><a href="{{ route('courses.show', $cours) }}">{{ $cours->titre }}</a></h2>
                        <p>{{ \Illuminate\Support\Str::limit($cours->description, 135) }}</p>
                    </div>

                    <dl class="meta-grid">
                        <div>
                            <dt>Formateur</dt>
                            <dd>{{ trim(($cours->formateur?->utilisateur?->prenom ?? '').' '.($cours->formateur?->utilisateur?->nom ?? '')) ?: 'Formini' }}</dd>
                        </div>
                        <div><dt>Langue</dt><dd>{{ $cours->langue }}</dd></div>
                        <div><dt>Duree</dt><dd>{{ $cours->duree_estimee }} min</dd></div>
                        <div><dt>Prix</dt><dd>{{ number_format((float) $cours->prix, 2) }} EUR</dd></div>
                    </dl>

                    <a class="card-action" href="{{ route('courses.show', $cours) }}">Voir details</a>
                </article>
            @endforeach
        </section>

        {{ $courses->links() }}
    @endif
@endsection
