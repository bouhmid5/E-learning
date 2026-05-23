@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>{{ isset($currentCategory) ? 'Cours: '.$currentCategory->nom : 'Catalogue des cours' }}</h1>
    </section>

    <form method="GET" action="{{ route('courses.index') }}" class="filter-bar">
        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Rechercher un cours">

        <select name="category">
            <option value="">Toutes les catégories</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id }}" @selected(($filters['category'] ?? '') === $categorie->id)>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>

        <input type="text" name="niveau" value="{{ $filters['niveau'] ?? '' }}" placeholder="Niveau">
        <input type="text" name="langue" value="{{ $filters['langue'] ?? '' }}" placeholder="Langue">
        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Prix min" min="0" step="0.01">
        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Prix max" min="0" step="0.01">
        <input type="number" name="min_duration" value="{{ $filters['min_duration'] ?? '' }}" placeholder="Durée min" min="0">
        <input type="number" name="max_duration" value="{{ $filters['max_duration'] ?? '' }}" placeholder="Durée max" min="0">
        <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Mot-clé">

        <select name="trainer">
            <option value="">Tous les formateurs</option>
            @foreach ($formateurs as $formateur)
                <option value="{{ $formateur->id }}" @selected(($filters['trainer'] ?? '') === $formateur->id)>
                    {{ $formateur->utilisateur?->prenom }} {{ $formateur->utilisateur?->nom }}
                </option>
            @endforeach
        </select>

        <select name="sort">
            @foreach (['date_publication' => 'Publication', 'titre' => 'Titre', 'prix' => 'Prix', 'duree_estimee' => 'Durée'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['sort'] ?? 'date_publication') === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <select name="direction">
            <option value="desc" @selected(($filters['direction'] ?? 'desc') === 'desc')>Descendant</option>
            <option value="asc" @selected(($filters['direction'] ?? '') === 'asc')>Ascendant</option>
        </select>

        <button type="submit">Filtrer</button>
    </form>

    @if ($courses->isEmpty())
        <section class="empty-state">
            <h2>Aucun cours publié trouvé</h2>
            <p>Essayez d'élargir votre recherche ou de retirer certains filtres.</p>
        </section>
    @else
        <section class="course-grid">
            @foreach ($courses as $cours)
                <article class="course-card">
                    <h2><a href="{{ route('courses.show', $cours) }}">{{ $cours->titre }}</a></h2>
                    <p>{{ $cours->description }}</p>
                    <dl>
                        <div><dt>Catégorie</dt><dd>{{ $cours->categorie?->nom }}</dd></div>
                        <div><dt>Niveau</dt><dd>{{ $cours->niveau }}</dd></div>
                        <div><dt>Langue</dt><dd>{{ $cours->langue }}</dd></div>
                        <div><dt>Prix</dt><dd>{{ number_format((float) $cours->prix, 2) }}</dd></div>
                        <div><dt>Durée</dt><dd>{{ $cours->duree_estimee }} min</dd></div>
                    </dl>
                </article>
            @endforeach
        </section>

        {{ $courses->links() }}
    @endif
@endsection

