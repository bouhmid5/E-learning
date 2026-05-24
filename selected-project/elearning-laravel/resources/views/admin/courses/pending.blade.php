@extends('layouts.app')

@section('title', 'Cours en attente')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Validation</p>
        <h1>Cours en attente</h1>
        <p>Publiez les cours conformes ou renvoyez-les avec un motif clair.</p>
    </section>

    @if ($courses->isEmpty())
        <section class="empty-state">
            <h2>Aucun cours en attente</h2>
            <p>Les cours soumis par les formateurs apparaitront ici.</p>
        </section>
    @else
        <section class="table-list">
            @foreach ($courses as $cours)
                <article class="course-card">
                    <div class="card-header-line">
                        <div>
                            <h2>{{ $cours->titre }}</h2>
                            <p>{{ $cours->categorie?->nom }} - {{ $cours->formateur?->utilisateur?->email }}</p>
                        </div>
                        <span class="badge badge-warning">{{ $cours->statut->value }}</span>
                    </div>
                    <p>{{ $cours->description }}</p>
                    <div class="inline-actions">
                        <form method="POST" action="{{ route('admin.courses.validate', $cours) }}" data-confirm="Publier ce cours ?">
                            @csrf
                            <button type="submit">Valider et publier</button>
                        </form>
                        <form method="POST" action="{{ route('admin.courses.reject', $cours) }}" data-confirm="Rejeter ce cours ?">
                            @csrf
                            <textarea name="motif_rejet" required placeholder="Motif du rejet"></textarea>
                            <button class="danger-button" type="submit">Rejeter</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>

        {{ $courses->links() }}
    @endif
@endsection
