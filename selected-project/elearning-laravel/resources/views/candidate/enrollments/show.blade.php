@extends('layouts.app')

@section('content')
    <article class="course-detail">
        <p><a href="{{ route('candidate.enrollments.index') }}">Retour a mes inscriptions</a></p>
        <h1>{{ $inscription->cours->titre }}</h1>
        <p>{{ $inscription->cours->description }}</p>
        <dl>
            <div><dt>Statut</dt><dd>{{ $inscription->statut->value }}</dd></div>
            <div><dt>Progression</dt><dd>{{ number_format((float) $inscription->progression, 2) }}%</dd></div>
            <div><dt>Categorie</dt><dd>{{ $inscription->cours->categorie?->nom }}</dd></div>
            <div><dt>Formateur</dt><dd>{{ $inscription->cours->formateur?->utilisateur?->prenom }} {{ $inscription->cours->formateur?->utilisateur?->nom }}</dd></div>
        </dl>
        <p><a href="{{ route('candidate.enrollments.lessons', $inscription) }}">Voir les lecons</a></p>
    </article>
@endsection
