@extends('layouts.app')

@section('title', 'Tableau de bord formateur')

@section('content')
    <section class="page-heading dashboard-hero">
        <div>
            <p class="eyebrow">Formini formateur</p>
            <h1>Tableau de bord Formini</h1>
            <p>Suivez vos cours, preparez vos contenus et soumettez les parcours prets a etre valides.</p>
        </div>
        <a class="button-link" href="{{ route('trainer.courses.create') }}">Creer un cours</a>
    </section>

    <section class="dashboard-grid" aria-label="Resume formateur">
        <article class="dashboard-stat">
            <span>Brouillons</span>
            <strong>{{ $draftCount }}</strong>
        </article>
        <article class="dashboard-stat">
            <span>En attente</span>
            <strong>{{ $pendingCount }}</strong>
        </article>
        <article class="dashboard-stat">
            <span>Publies</span>
            <strong>{{ $publishedCount }}</strong>
        </article>
        <article class="dashboard-stat">
            <span>Rejetes</span>
            <strong>{{ $rejectedCount }}</strong>
        </article>
    </section>

    <section class="table-panel">
        <div class="card-header-line">
            <h2>Cours recents</h2>
            <a href="{{ route('trainer.courses.index') }}">Voir mes cours</a>
        </div>

        @if ($recentCourses->isEmpty())
            <div class="profile-placeholder">
                <strong>Aucun cours cree</strong>
                <p>Creez un brouillon pour commencer a structurer votre formation.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Categorie</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentCourses as $cours)
                        <tr>
                            <td>{{ $cours->titre }}</td>
                            <td>{{ $cours->categorie?->nom ?? 'Non definie' }}</td>
                            <td><x-status-badge>{{ $cours->statut->value }}</x-status-badge></td>
                            <td><a href="{{ route('trainer.courses.show', $cours) }}">Ouvrir</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
@endsection
