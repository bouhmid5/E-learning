@extends('layouts.app')

@section('title', 'Tableau de bord candidat')

@section('content')
    <section class="page-heading dashboard-hero">
        <div>
            <p class="eyebrow">Formini candidat</p>
            <h1>Tableau de bord Formini</h1>
            <p>Bienvenue {{ auth()->user()->prenom }}. Retrouvez vos cours, votre progression, vos resultats et vos certificats.</p>
        </div>
        <a class="button-link" href="{{ route('courses.index') }}">Explorer le catalogue</a>
    </section>

    <section class="dashboard-grid" aria-label="Resume candidat">
        <article class="dashboard-stat">
            <span>Cours en cours</span>
            <strong>{{ $inscriptionsCount }}</strong>
        </article>
        <article class="dashboard-stat">
            <span>Certificats</span>
            <strong>{{ $certificatesCount }}</strong>
        </article>
        <article class="dashboard-stat">
            <span>Evaluations</span>
            <strong>0</strong>
        </article>
    </section>

    <section class="table-panel">
        <div class="card-header-line">
            <h2>Mes cours recents</h2>
            <a href="{{ route('candidate.enrollments.index') }}">Voir tout</a>
        </div>

        @if ($inscriptions->isEmpty())
            <div class="profile-placeholder">
                <strong>Aucun cours pour le moment</strong>
                <p>Explorez le catalogue Formini pour commencer un parcours.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Categorie</th>
                        <th>Progression</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inscriptions as $inscription)
                        <tr>
                            <td>{{ $inscription->cours?->titre }}</td>
                            <td>{{ $inscription->cours?->categorie?->nom ?? 'Non definie' }}</td>
                            <td>{{ number_format((float) $inscription->progression, 0) }}%</td>
                            <td><a href="{{ route('candidate.enrollments.show', $inscription) }}">Continuer</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <section class="action-grid">
        <article class="action-card">
            <span>Evaluations</span>
            <strong>Prochaines evaluations</strong>
            <p>Aucune evaluation planifiee n'est disponible ici pour le moment.</p>
        </article>
        <article class="action-card">
            <span>Certificats</span>
            <strong>Disponibilite</strong>
            <p>Les certificats apparaissent lorsque la progression et les evaluations sont validees.</p>
        </article>
    </section>
@endsection
