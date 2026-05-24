@extends('layouts.app')

@section('title', 'Tableau de bord candidat')

@section('content')
    <section class="page-heading dashboard-hero">
        <div>
            <p class="eyebrow">Espace candidat</p>
            <h1>Votre parcours en un coup d'oeil</h1>
            <p>Reprenez vos cours, consultez vos resultats et retrouvez vos certificats sans chercher.</p>
        </div>
        <a class="button-link" href="{{ route('courses.index') }}">Explorer le catalogue</a>
    </section>

    <section class="action-grid">
        <a class="action-card" href="{{ route('candidate.enrollments.index') }}">
            <span>Mes cours</span>
            <strong>Continuer l'apprentissage</strong>
            <p>Acceder aux lecons et a votre progression.</p>
        </a>
        <a class="action-card" href="{{ route('candidate.results') }}">
            <span>Resultats</span>
            <strong>Suivre mes evaluations</strong>
            <p>Voir les scores, statuts et feedbacks.</p>
        </a>
        <a class="action-card" href="{{ route('candidate.certificates.index') }}">
            <span>Certificats</span>
            <strong>Telecharger mes attestations</strong>
            <p>Retrouver les certificats generes.</p>
        </a>
    </section>
@endsection
