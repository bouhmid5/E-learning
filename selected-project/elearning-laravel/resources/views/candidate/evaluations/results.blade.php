@extends('layouts.app')

@section('title', 'Resultats')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Evaluations</p>
        <h1>Resultats</h1>
        <p>Consultez vos scores et vos feedbacks de correction.</p>
    </section>

    @forelse ($soumissions as $soumission)
        <article class="course-card">
            <div class="card-header-line">
                <h2><a href="{{ route('candidate.submissions.show', $soumission) }}">{{ $soumission->evaluation->titre }}</a></h2>
                <span class="badge {{ $soumission->reussi ? '' : 'badge-danger' }}">{{ $soumission->reussi ? 'Reussi' : 'Echoue' }}</span>
            </div>
            <p>{{ $soumission->evaluation->cours->titre }}</p>
            <dl class="meta-grid">
                <div><dt>Score</dt><dd>{{ $soumission->score_obtenu }}</dd></div>
            </dl>
        </article>
    @empty
        <section class="empty-state">
            <h2>Aucun resultat</h2>
            <p>Les resultats apparaitront apres soumission d'une evaluation.</p>
        </section>
    @endforelse

    {{ $soumissions->links() }}
@endsection
