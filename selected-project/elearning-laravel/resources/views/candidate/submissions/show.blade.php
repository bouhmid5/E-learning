@extends('layouts.app')

@section('content')
    <section class="course-detail">
        <h1>Résultat - {{ $soumission->evaluation->titre }}</h1>
        <p>Score: {{ $soumission->score_obtenu }}</p>
        <p>Statut: {{ $soumission->reussi ? 'Réussi' : 'Échoué' }}</p>

        <h2>Feedback automatique</h2>
        <pre>{{ $soumission->feedback_automatique }}</pre>

        <h2>Réponses</h2>
        @foreach ($soumission->reponsesCandidats as $reponse)
            <article class="course-card">
                <h3>{{ $reponse->question->enonce }}</h3>
                <p>{{ $reponse->valeur }}</p>
                <p>{{ $reponse->est_correcte ? 'Correct' : 'Incorrect' }} · {{ $reponse->points_obtenus }} point(s)</p>
            </article>
        @endforeach
    </section>
@endsection

