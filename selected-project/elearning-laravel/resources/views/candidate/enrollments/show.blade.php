@extends('layouts.app')

@section('title', $inscription->cours->titre)

@section('content')
    @php($progress = (float) $inscription->progression)

    <article class="course-detail">
        <p><a class="muted-link" href="{{ route('candidate.enrollments.index') }}">Retour a mes cours</a></p>
        <div class="detail-hero">
            <div>
                <p class="eyebrow">Cours inscrit</p>
                <h1>{{ $inscription->cours->titre }}</h1>
                <p>{{ $inscription->cours->description }}</p>
            </div>
            <a class="button-link" href="{{ route('candidate.enrollments.lessons', $inscription) }}">Voir les lecons</a>
        </div>

        <dl>
            <div><dt>Statut</dt><dd>{{ $inscription->statut->value }}</dd></div>
            <div><dt>Progression</dt><dd>{{ number_format($progress, 2) }}%</dd></div>
            <div><dt>Categorie</dt><dd>{{ $inscription->cours->categorie?->nom }}</dd></div>
            <div><dt>Formateur</dt><dd>{{ $inscription->cours->formateur?->utilisateur?->prenom }} {{ $inscription->cours->formateur?->utilisateur?->nom }}</dd></div>
        </dl>

        <div class="progress-track" aria-label="Progression">
            <span class="progress-fill" style="width: {{ min(100, max(0, $progress)) }}%"></span>
        </div>

        <div class="hero-actions">
            <a class="button-link" href="{{ route('candidate.enrollments.lessons', $inscription) }}">Continuer</a>
            <a href="{{ route('candidate.enrollments.certificate.eligibility', $inscription) }}">Voir l'eligibilite certificat</a>
        </div>
    </article>
@endsection
