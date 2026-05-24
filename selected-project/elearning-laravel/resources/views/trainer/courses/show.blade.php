@extends('layouts.app')

@section('title', $cours->titre)

@section('content')
    <section class="course-detail">
        <p><a class="muted-link" href="{{ route('trainer.courses.index') }}">Retour a mes cours</a></p>

        <div class="detail-hero">
            <div>
                <p class="eyebrow">Atelier cours</p>
                <h1>{{ $cours->titre }}</h1>
                <p>{{ $cours->description }}</p>
            </div>
            <span class="badge">{{ $cours->statut->value }}</span>
        </div>

        <div class="hero-actions">
            @can('update', $cours)
                <a class="button-link" href="{{ route('trainer.courses.edit', $cours) }}">Modifier le cours</a>
                <form method="POST" action="{{ route('trainer.courses.submit', $cours) }}" data-confirm="Soumettre ce cours pour validation ?">
                    @csrf
                    <button type="submit">Soumettre</button>
                </form>
            @endcan
            <a href="{{ route('trainer.courses.evaluations.index', $cours) }}">Evaluations</a>
        </div>

        @error('cours')
            <p class="field-error">{{ $message }}</p>
        @enderror

        @can('update', $cours)
            <section class="form-card">
                <h2>Ajouter une lecon</h2>
                <form method="POST" action="{{ route('trainer.courses.lessons.store', $cours) }}" class="filter-bar">
                    @csrf
                    <input type="text" name="titre" placeholder="Titre" required>
                    <input type="number" name="ordre" placeholder="Ordre" min="1" required>
                    <input type="number" name="duree_estimee" placeholder="Duree" min="0">
                    <input type="text" name="description" placeholder="Description">
                    <button type="submit">Ajouter</button>
                </form>
            </section>
        @endcan

        <section class="learning-list">
            <h2>Lecons</h2>
            @forelse ($cours->lecons as $lecon)
                <article class="course-card">
                    <div class="card-header-line">
                        <h3>{{ $lecon->ordre }}. {{ $lecon->titre }}</h3>
                        <span class="badge">{{ $lecon->duree_estimee ?? 0 }} min</span>
                    </div>
                    <p>{{ $lecon->description }}</p>

                    @can('update', $cours)
                        <form method="POST" action="{{ route('trainer.lessons.update', $lecon) }}" class="filter-bar">
                            @csrf
                            @method('PUT')
                            <input type="text" name="titre" value="{{ $lecon->titre }}" required>
                            <input type="number" name="ordre" value="{{ $lecon->ordre }}" min="1" required>
                            <input type="number" name="duree_estimee" value="{{ $lecon->duree_estimee }}" min="0">
                            <input type="text" name="description" value="{{ $lecon->description }}">
                            <button type="submit">Mettre a jour</button>
                        </form>

                        <form method="POST" action="{{ route('trainer.lessons.destroy', $lecon) }}" data-confirm="Supprimer cette lecon ?">
                            @csrf
                            @method('DELETE')
                            <button class="danger-button" type="submit">Supprimer la lecon</button>
                        </form>

                        <form method="POST" action="{{ route('trainer.lessons.resources.store', $lecon) }}" enctype="multipart/form-data" class="filter-bar">
                            @csrf
                            <input type="text" name="titre" placeholder="Titre ressource" required>
                            <select name="type">
                                <option value="DOCUMENT">Document</option>
                                <option value="VIDEO">Video</option>
                                <option value="LIEN">Lien</option>
                            </select>
                            <input type="url" name="url" placeholder="URL pour lien">
                            <input type="file" name="fichier" accept=".pdf,.mp4,.webm,.doc,.docx,.ppt,.pptx,.txt,.zip">
                            <input type="number" name="ordre" value="1" min="1" required>
                            <button type="submit">Ajouter ressource</button>
                        </form>
                    @endcan
                </article>
            @empty
                <section class="empty-state">
                    <h2>Aucune lecon</h2>
                    <p>Ajoutez au moins une lecon avant de soumettre le cours.</p>
                </section>
            @endforelse
        </section>
    </section>
@endsection
