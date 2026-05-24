@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Évaluations - {{ $cours->titre }}</h1>
        <p><a href="{{ route('trainer.courses.evaluations.create', $cours) }}">Créer une évaluation</a></p>
    </section>

    @if ($evaluations->isEmpty())
        <section class="empty-state">
            <h2>Aucune évaluation</h2>
            <p>Ajoutez une première évaluation pour ce cours.</p>
        </section>
    @else
        <section class="course-grid">
            @foreach ($evaluations as $evaluation)
                <article class="course-card">
                    <h2><a href="{{ route('trainer.evaluations.edit', $evaluation) }}">{{ $evaluation->titre }}</a></h2>
                    <p>{{ $evaluation->type_evaluation->value }} · seuil {{ $evaluation->seuil_reussite }} · {{ $evaluation->questions_count }} question(s)</p>
                </article>
            @endforeach
        </section>

        {{ $evaluations->links() }}
    @endif
@endsection

