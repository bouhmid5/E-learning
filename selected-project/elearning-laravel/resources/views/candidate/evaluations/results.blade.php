@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Resultats</h1>
    </section>

    @forelse ($soumissions as $soumission)
        <article class="course-card">
            <h2><a href="{{ route('candidate.submissions.show', $soumission) }}">{{ $soumission->evaluation->titre }}</a></h2>
            <p>{{ $soumission->evaluation->cours->titre }} - {{ $soumission->score_obtenu }} - {{ $soumission->reussi ? 'Reussi' : 'Echoue' }}</p>
        </article>
    @empty
        <section class="empty-state">
            <h2>Aucun resultat</h2>
            <p>Les resultats apparaitront apres soumission d'une evaluation.</p>
        </section>
    @endforelse

    {{ $soumissions->links() }}
@endsection
