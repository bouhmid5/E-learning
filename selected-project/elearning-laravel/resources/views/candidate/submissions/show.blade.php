@extends('layouts.app')

@section('title', 'Resultat')

@section('content')
    <section class="course-detail">
        <div class="detail-hero">
            <div>
                <p class="eyebrow">Resultat</p>
                <h1>{{ $soumission->evaluation->titre }}</h1>
                <p>{{ $soumission->evaluation->cours->titre }}</p>
            </div>
            <span class="badge {{ $soumission->reussi ? '' : 'badge-danger' }}">{{ $soumission->reussi ? 'Reussi' : 'Echoue' }}</span>
        </div>

        <dl>
            <div><dt>Score</dt><dd>{{ $soumission->score_obtenu }}</dd></div>
        </dl>

        <h2>Feedback automatique</h2>
        <pre>{{ $soumission->feedback_automatique }}</pre>

        <h2>Reponses</h2>
        <div class="learning-list">
            @forelse ($soumission->reponsesCandidats as $reponse)
                <article class="course-card">
                    <div class="card-header-line">
                        <h3>{{ $reponse->question->enonce }}</h3>
                        <span class="badge {{ $reponse->est_correcte ? '' : 'badge-danger' }}">{{ $reponse->est_correcte ? 'Correct' : 'Incorrect' }}</span>
                    </div>
                    <p>{{ $reponse->valeur }}</p>
                    <p>{{ $reponse->points_obtenus }} point(s)</p>
                </article>
            @empty
                <p>Aucune reponse enregistree.</p>
            @endforelse
        </div>
    </section>
@endsection
