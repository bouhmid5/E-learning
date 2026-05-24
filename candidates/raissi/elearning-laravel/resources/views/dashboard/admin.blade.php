@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
    <section class="page-heading dashboard-hero">
        <div>
            <p class="eyebrow">Formini administration</p>
            <h1>Tableau de bord Formini</h1>
            <p>Surveillez les comptes, les formateurs, les cours a valider et la structure du catalogue.</p>
        </div>
        <a class="button-link" href="{{ route('admin.courses.pending') }}">Cours a valider</a>
    </section>

    <section class="dashboard-grid" aria-label="Resume administrateur">
        <article class="dashboard-stat">
            <span>Formateurs en attente</span>
            <strong>{{ $pendingTrainersCount }}</strong>
        </article>
        <article class="dashboard-stat">
            <span>Cours en attente</span>
            <strong>{{ $pendingCoursesCount }}</strong>
        </article>
        <article class="dashboard-stat">
            <span>Utilisateurs</span>
            <strong>{{ $usersCount }}</strong>
        </article>
        <article class="dashboard-stat">
            <span>Categories</span>
            <strong>{{ $categoriesCount }}</strong>
        </article>
    </section>

    <section class="table-panel">
        <div class="card-header-line">
            <h2>Cours en attente</h2>
            <a href="{{ route('admin.courses.pending') }}">Tout verifier</a>
        </div>

        @if ($pendingCourses->isEmpty())
            <div class="profile-placeholder">
                <strong>Aucun cours a valider</strong>
                <p>Les cours soumis par les formateurs apparaitront ici.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Formateur</th>
                        <th>Categorie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingCourses as $cours)
                        <tr>
                            <td>{{ $cours->titre }}</td>
                            <td>{{ trim(($cours->formateur?->utilisateur?->prenom ?? '').' '.($cours->formateur?->utilisateur?->nom ?? '')) ?: 'Formateur' }}</td>
                            <td>{{ $cours->categorie?->nom ?? 'Non definie' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <section class="table-panel">
        <div class="card-header-line">
            <h2>Formateurs en attente</h2>
            <a href="{{ route('admin.trainers.pending') }}">Examiner</a>
        </div>

        @if ($pendingTrainers->isEmpty())
            <div class="profile-placeholder">
                <strong>Aucun formateur en attente</strong>
                <p>Les nouvelles demandes de validation apparaitront ici.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Specialite</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingTrainers as $formateur)
                        <tr>
                            <td>{{ trim(($formateur->utilisateur?->prenom ?? '').' '.($formateur->utilisateur?->nom ?? '')) ?: 'Formateur' }}</td>
                            <td>{{ $formateur->utilisateur?->email }}</td>
                            <td>{{ $formateur->specialite }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>
@endsection
