@extends('layouts.app')

@section('title', $cours->titre)

@php
    $webUser = auth()->user();
    $adminUser = auth('admin')->user();
    $trainerName = trim(($cours->formateur?->utilisateur?->prenom ?? '').' '.($cours->formateur?->utilisateur?->nom ?? ''));
    $hasLocalImage = $cours->image_url && ! \Illuminate\Support\Str::startsWith($cours->image_url, ['http://', 'https://']);
@endphp

@section('content')
    <article class="course-detail">
        <p><a href="{{ route('courses.index') }}">Retour au catalogue</a></p>

        <section class="detail-hero">
            <div>
                <p class="eyebrow">{{ $cours->categorie?->nom ?? 'Cours publie' }}</p>
                <h1>{{ $cours->titre }}</h1>
                <p>{{ $cours->description }}</p>
            </div>

            <div class="course-visual" aria-label="Visuel du cours">
                @if ($hasLocalImage)
                    <img src="{{ asset(ltrim($cours->image_url, '/')) }}" alt="{{ $cours->titre }}">
                @else
                    <span class="course-visual-placeholder">Formini</span>
                @endif
            </div>
        </section>

        <dl class="detail-meta">
            <div><dt>Categorie</dt><dd>{{ $cours->categorie?->nom ?? 'Non definie' }}</dd></div>
            <div><dt>Formateur</dt><dd>{{ $trainerName ?: 'Formateur Formini' }}</dd></div>
            <div><dt>Niveau</dt><dd>{{ $cours->niveau }}</dd></div>
            <div><dt>Langue</dt><dd>{{ $cours->langue }}</dd></div>
            <div><dt>Duree estimee</dt><dd>{{ $cours->duree_estimee }} min</dd></div>
            <div><dt>Prix</dt><dd>{{ number_format((float) $cours->prix, 2) }} EUR</dd></div>
        </dl>

        <section class="ui-card detail-section">
            <h2>Acces au cours</h2>
            @if (! $webUser && ! $adminUser)
                <p class="muted">Connectez-vous avec un compte candidat pour demander l'inscription.</p>
                <a href="{{ route('login') }}" class="button-link">Se connecter pour s'inscrire</a>
            @else
                @if ($webUser?->candidat && $candidateEnrollment)
                    <p class="muted">Vous etes deja inscrit a ce cours.</p>
                    <a href="{{ route('candidate.enrollments.show', $candidateEnrollment) }}" class="button-link">Continuer le cours</a>
                @elseif ($webUser?->candidat)
                    <form method="POST" action="{{ route('courses.enroll', $cours) }}">
                        @csrf
                        <button type="submit" data-loading-text="Inscription...">S'inscrire au cours</button>
                    </form>
                @elseif ($webUser?->formateur || $adminUser)
                    <p class="muted">Les inscriptions sont reservees aux candidats.</p>
                @endif
            @endif
        </section>

        <section class="detail-section">
            <h2>Apercu des lecons</h2>
            @forelse ($cours->lecons->sortBy('ordre') as $lecon)
                <article class="lesson-row">
                    <h3>{{ $lecon->ordre }}. {{ $lecon->titre }}</h3>
                    <p>{{ $lecon->description ?: 'Description non renseignee.' }}</p>
                </article>
            @empty
                <x-empty-state title="Aucune lecon visible">
                    Les lecons seront affichees lorsque le formateur les aura preparees.
                </x-empty-state>
            @endforelse
        </section>

        <section class="ui-card detail-section">
            <h2>Evaluation et certification</h2>
            @if ($cours->evaluations->isEmpty())
                <p class="muted">Aucune evaluation n'est associee a ce cours pour le moment.</p>
            @else
                <p class="muted">{{ $cours->evaluations->count() }} evaluation(s) associee(s) a ce parcours.</p>
            @endif
            <p class="muted">La certification devient disponible lorsque les conditions de progression et de reussite sont validees.</p>
        </section>
    </article>
@endsection
