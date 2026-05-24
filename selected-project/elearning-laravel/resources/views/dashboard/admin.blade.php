@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Administration</p>
        <h1>Tableau de bord administrateur</h1>
        <p>Surveillez les utilisateurs, les validations formateur et la publication des cours.</p>
    </section>

    <section class="action-grid">
        <a class="action-card" href="{{ route('admin.users.index') }}">
            <span>Utilisateurs</span>
            <strong>Gérer les comptes</strong>
        </a>
        <a class="action-card" href="{{ route('admin.trainers.pending') }}">
            <span>Formateurs</span>
            <strong>Valider les demandes</strong>
        </a>
        <a class="action-card" href="{{ route('admin.courses.pending') }}">
            <span>Cours</span>
            <strong>Modérer les publications</strong>
        </a>
        <a class="action-card" href="{{ route('admin.categories.index') }}">
            <span>Catégories</span>
            <strong>Structurer le catalogue</strong>
        </a>
    </section>
@endsection
