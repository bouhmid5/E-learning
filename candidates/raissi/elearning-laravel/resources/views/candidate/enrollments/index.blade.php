@extends('layouts.app')

@section('title', 'Mes cours')

@section('content')
    <section class="page-heading split-heading">
        <div>
            <p class="eyebrow">Apprentissage</p>
            <h1>Mes cours</h1>
            <p>Suivez vos inscriptions et reprenez rapidement un parcours.</p>
        </div>
        <a class="button-link" href="{{ route('courses.index') }}">Trouver un cours</a>
    </section>

    @if ($inscriptions->isEmpty())
        <section class="empty-state">
            <h2>Aucune inscription</h2>
            <p>Explorez le catalogue pour commencer un premier cours.</p>
            <a class="button-link" href="{{ route('courses.index') }}">Voir le catalogue</a>
        </section>
    @else
        <section class="course-grid">
            @foreach ($inscriptions as $inscription)
                @php($progress = (float) $inscription->progression)
                <article class="course-card">
                    <div class="card-header-line">
                        <span class="badge">{{ $inscription->statut->value }}</span>
                        <span>{{ number_format($progress, 0) }}%</span>
                    </div>
                    <h2><a href="{{ route('candidate.enrollments.show', $inscription) }}">{{ $inscription->cours->titre }}</a></h2>
                    <p>{{ $inscription->cours->categorie?->nom ?? 'Sans categorie' }}</p>
                    <div class="progress-track" aria-label="Progression">
                        <span class="progress-fill" style="width: {{ min(100, max(0, $progress)) }}%"></span>
                    </div>
                    <a class="card-action" href="{{ route('candidate.enrollments.show', $inscription) }}">Ouvrir</a>
                </article>
            @endforeach
        </section>

        {{ $inscriptions->links() }}
    @endif
@endsection
