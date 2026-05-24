@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Résultats</h1>
    </section>

    @forelse ($soumissions as $soumission)
        <article class="course-card">
            <h2><a href="{{ route('candidate.submissions.show', $soumission) }}">{{ $soumission->evaluation->titre }}</a></h2>
            <p>{{ $soumission->evaluation->cours->titre }} · {{ $soumission->score_obtenu }} · {{ $soumission->reussi ? 'Réussi' : 'Échoué' }}</p>
        </article>
    @empty
        <section class="empty-state">
            <h2>Aucun résultat</h2>
            <p>Les résultats apparaîtront après soumission d'une évaluation.</p>
        </section>
    @endforelse

    {{ $soumissions->links() }}
@endsection

