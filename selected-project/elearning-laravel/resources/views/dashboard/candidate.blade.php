@extends('layouts.app')

@section('title', 'Tableau de bord candidat')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Espace candidat</p>
        <h1>Tableau de bord candidat</h1>
        <p>Retrouvez vos cours, votre progression, vos évaluations et vos certificats.</p>
    </section>

    <section class="action-grid">
        <a class="action-card" href="{{ route('candidate.enrollments.index') }}">
            <span>Mes cours</span>
            <strong>Continuer l'apprentissage</strong>
        </a>
        <a class="action-card" href="{{ route('candidate.results') }}">
            <span>Résultats</span>
            <strong>Consulter mes évaluations</strong>
        </a>
        <a class="action-card" href="{{ route('candidate.certificates.index') }}">
            <span>Certificats</span>
            <strong>Télécharger mes attestations</strong>
        </a>
        <a class="action-card" href="{{ route('courses.index') }}">
            <span>Catalogue</span>
            <strong>Trouver un nouveau cours</strong>
        </a>
    </section>
@endsection
