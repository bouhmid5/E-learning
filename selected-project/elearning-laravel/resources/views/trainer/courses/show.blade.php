@extends('layouts.app')

@section('content')
    <section class="course-detail">
        <p><a href="{{ route('trainer.courses.index') }}">Retour a mes cours</a></p>
        <h1>{{ $cours->titre }}</h1>
        <p>{{ $cours->description }}</p>
        <p>Statut: {{ $cours->statut->value }}</p>

        @error('cours')
            <p class="field-error">{{ $message }}</p>
        @enderror

        @can('update', $cours)
            <p><a href="{{ route('trainer.courses.edit', $cours) }}">Modifier le cours</a></p>
            <form method="POST" action="{{ route('trainer.courses.submit', $cours) }}" data-confirm="Soumettre ce cours pour validation ?">
                @csrf
                <button type="submit">Soumettre pour validation</button>
            </form>
        @endcan

        <section>
            <h2>Ajouter une lecon</h2>
            @can('update', $cours)
                <form method="POST" action="{{ route('trainer.courses.lessons.store', $cours) }}" class="filter-bar">
                    @csrf
                    <input type="text" name="titre" placeholder="Titre" required>
                    <input type="number" name="ordre" placeholder="Ordre" min="1" required>
                    <input type="number" name="duree_estimee" placeholder="Duree" min="0">
                    <input type="text" name="description" placeholder="Description">
                    <button type="submit">Ajouter</button>
                </form>
            @endcan
        </section>

        <section>
            <h2>Lecons</h2>
            @forelse ($cours->lecons as $lecon)
                <article class="course-card">
                    <h3>{{ $lecon->ordre }}. {{ $lecon->titre }}</h3>
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
                            <button type="submit">Supprimer la lecon</button>
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
                <p>Aucune lecon pour ce cours.</p>
            @endforelse
        </section>
    </section>
@endsection
