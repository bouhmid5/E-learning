@extends('layouts.app')

@section('content')
    <section class="course-detail">
        <h1>Resultat - {{ $soumission->evaluation->titre }}</h1>
        <p>Score: {{ $soumission->score_obtenu }}</p>
        <p>Statut: {{ $soumission->reussi ? 'Reussi' : 'Echoue' }}</p>

        <h2>Feedback automatique</h2>
        <pre>{{ $soumission->feedback_automatique }}</pre>

        <h2>Reponses</h2>
        @forelse ($soumission->reponsesCandidats as $reponse)
            <article class="course-card">
                <h3>{{ $reponse->question->enonce }}</h3>
                <p>{{ $reponse->valeur }}</p>
                <p>{{ $reponse->est_correcte ? 'Correct' : 'Incorrect' }} - {{ $reponse->points_obtenus }} point(s)</p>
            </article>
        @empty
            <p>Aucune reponse enregistree.</p>
        @endforelse
    </section>
@endsection
