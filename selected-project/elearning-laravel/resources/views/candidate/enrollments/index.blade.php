@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Mes inscriptions</h1>
    </section>

    <section>
        @forelse ($inscriptions as $inscription)
            <article class="course-card">
                <h2><a href="{{ route('candidate.enrollments.show', $inscription) }}">{{ $inscription->cours->titre }}</a></h2>
                <p>{{ $inscription->cours->categorie?->nom }} · {{ $inscription->statut->value }}</p>
                <p>Progression: {{ number_format((float) $inscription->progression, 2) }}%</p>
            </article>
        @empty
            <p>Aucune inscription pour le moment.</p>
        @endforelse
    </section>

    {{ $inscriptions->links() }}
@endsection
