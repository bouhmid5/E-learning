@extends('layouts.app')

@section('title', 'Tableau de bord formateur')

@section('content')
    <section class="page-heading dashboard-hero">
        <div>
            <p class="eyebrow">Espace formateur</p>
            <h1>Organisez vos contenus de formation</h1>
            <p>Creez les cours, structurez les lecons, ajoutez les ressources et preparez les evaluations.</p>
        </div>
        <a class="button-link" href="{{ route('trainer.courses.create') }}">Nouveau cours</a>
    </section>

    <section class="action-grid">
        <a class="action-card" href="{{ route('trainer.courses.index') }}">
            <span>Mes cours</span>
            <strong>Gerer le contenu</strong>
            <p>Modifier les brouillons et consulter les statuts.</p>
        </a>
        <a class="action-card" href="{{ route('trainer.courses.create') }}">
            <span>Brouillon</span>
            <strong>Demarrer un cours</strong>
            <p>Creer un nouveau parcours avant soumission.</p>
        </a>
    </section>
@endsection
