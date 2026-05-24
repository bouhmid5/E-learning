@extends('layouts.app')

@section('title', $cours->titre)

@section('content')
    <article class="course-detail">
        <p><a href="{{ route('courses.index') }}">Retour au catalogue</a></p>

        <section class="detail-hero">
            <div>
                <p class="eyebrow">{{ $cours->categorie?->nom ?? 'Cours publie' }}</p>
                <h1>{{ $cours->titre }}</h1>
                <p>{{ $cours->description }}</p>
            </div>

            @auth
                @if (auth()->user()?->candidat)
                    <form method="POST" action="{{ route('courses.enroll', $cours) }}">
                        @csrf
                        <button type="submit">S'inscrire au cours</button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="button-link">Se connecter pour s'inscrire</a>
            @endauth
        </section>

        <dl class="detail-meta">
            <div><dt>Categorie</dt><dd>{{ $cours->categorie?->nom }}</dd></div>
            <div><dt>Formateur</dt><dd>{{ $cours->formateur?->utilisateur?->prenom }} {{ $cours->formateur?->utilisateur?->nom }}</dd></div>
            <div><dt>Niveau</dt><dd>{{ $cours->niveau }}</dd></div>
            <div><dt>Langue</dt><dd>{{ $cours->langue }}</dd></div>
            <div><dt>Prix</dt><dd>{{ number_format((float) $cours->prix, 2) }} EUR</dd></div>
            <div><dt>Duree estimee</dt><dd>{{ $cours->duree_estimee }} min</dd></div>
        </dl>

        <section>
            <h2>Lecons</h2>
            @forelse ($cours->lecons as $lecon)
                <article class="lesson-row">
                    <h3>{{ $lecon->ordre }}. {{ $lecon->titre }}</h3>
                    <p>{{ $lecon->description }}</p>
                </article>
            @empty
                <p>Aucune lecon n'est visible pour ce cours.</p>
            @endforelse
        </section>
    </article>
@endsection
