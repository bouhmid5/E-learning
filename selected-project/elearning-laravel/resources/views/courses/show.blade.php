@extends('layouts.app')

@section('content')
    <article class="course-detail">
        <p><a href="{{ route('courses.index') }}">Retour au catalogue</a></p>
        <h1>{{ $cours->titre }}</h1>
        <p>{{ $cours->description }}</p>

        <dl>
            <div><dt>Catégorie</dt><dd>{{ $cours->categorie?->nom }}</dd></div>
            <div><dt>Formateur</dt><dd>{{ $cours->formateur?->utilisateur?->prenom }} {{ $cours->formateur?->utilisateur?->nom }}</dd></div>
            <div><dt>Niveau</dt><dd>{{ $cours->niveau }}</dd></div>
            <div><dt>Langue</dt><dd>{{ $cours->langue }}</dd></div>
            <div><dt>Prix</dt><dd>{{ number_format((float) $cours->prix, 2) }}</dd></div>
            <div><dt>Durée estimée</dt><dd>{{ $cours->duree_estimee }} min</dd></div>
        </dl>

        <section>
            <h2>Leçons</h2>
            @forelse ($cours->lecons as $lecon)
                <article>
                    <h3>{{ $lecon->ordre }}. {{ $lecon->titre }}</h3>
                    <p>{{ $lecon->description }}</p>
                </article>
            @empty
                <p>Aucune leçon n'est visible pour ce cours.</p>
            @endforelse
        </section>
    </article>
@endsection

