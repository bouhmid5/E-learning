@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <section class="page-heading split-heading">
        <div>
            <p class="eyebrow">Administration</p>
            <h1>Categories</h1>
            <p>Organisez le catalogue public en domaines et sous-domaines.</p>
        </div>
        <a class="button-link" href="{{ route('admin.categories.create') }}">Creer une categorie</a>
    </section>

    @if ($categories->isEmpty())
        <section class="empty-state">
            <h2>Aucune categorie</h2>
            <p>Ajoutez une premiere categorie pour organiser les cours.</p>
        </section>
    @else
        <section class="course-grid">
            @foreach ($categories as $categorie)
                <article class="course-card">
                    <div class="card-header-line">
                        <span class="badge">{{ $categorie->parent?->nom ?? 'Racine' }}</span>
                    </div>
                    <h2>{{ $categorie->nom }}</h2>
                    <p>{{ $categorie->description }}</p>
                    <div class="inline-actions">
                        <a href="{{ route('admin.categories.edit', $categorie) }}">Modifier</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}" data-confirm="Supprimer cette categorie ?">
                            @csrf
                            @method('DELETE')
                            <button class="danger-button" type="submit">Supprimer</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>

        {{ $categories->links() }}
    @endif
@endsection
