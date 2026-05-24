@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
    <section class="page-heading dashboard-hero">
        <div>
            <p class="eyebrow">Administration</p>
            <h1>Pilotez les validations de la plateforme</h1>
            <p>Controlez les comptes, les formateurs, les cours en attente et la structure du catalogue.</p>
        </div>
        <a class="button-link" href="{{ route('admin.courses.pending') }}">Cours a valider</a>
    </section>

    <section class="action-grid">
        <a class="action-card" href="{{ route('admin.users.index') }}">
            <span>Utilisateurs</span>
            <strong>Gerer les comptes</strong>
            <p>Activer, desactiver ou verifier les statuts.</p>
        </a>
        <a class="action-card" href="{{ route('admin.trainers.pending') }}">
            <span>Formateurs</span>
            <strong>Valider les demandes</strong>
            <p>Controler profils et justificatifs.</p>
        </a>
        <a class="action-card" href="{{ route('admin.courses.pending') }}">
            <span>Cours</span>
            <strong>Moderer les publications</strong>
            <p>Publier ou rejeter avec motif.</p>
        </a>
        <a class="action-card" href="{{ route('admin.categories.index') }}">
            <span>Categories</span>
            <strong>Structurer le catalogue</strong>
            <p>Organiser les domaines de formation.</p>
        </a>
    </section>
@endsection
