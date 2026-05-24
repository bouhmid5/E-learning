@extends('layouts.app')

@section('title', 'Tableau de bord formateur')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Espace formateur</p>
        <h1>Tableau de bord formateur</h1>
        <p>Créez vos cours, organisez les leçons et préparez les évaluations.</p>
    </section>

    <section class="action-grid">
        <a class="action-card" href="{{ route('trainer.courses.index') }}">
            <span>Mes cours</span>
            <strong>Gérer le contenu</strong>
        </a>
        <a class="action-card" href="{{ route('trainer.courses.create') }}">
            <span>Nouveau cours</span>
            <strong>Démarrer un brouillon</strong>
        </a>
    </section>
@endsection
