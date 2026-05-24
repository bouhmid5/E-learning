@extends('layouts.app')

@section('title', 'Evaluations')

@section('content')
    <section class="page-heading split-heading">
        <div>
            <p class="eyebrow">Evaluations</p>
            <h1>{{ $cours->titre }}</h1>
            <p>Gerez les quiz, examens et devoirs rattaches a ce cours.</p>
        </div>
        <a class="button-link" href="{{ route('trainer.courses.evaluations.create', $cours) }}">Creer une evaluation</a>
    </section>

    @if ($evaluations->isEmpty())
        <section class="empty-state">
            <h2>Aucune evaluation</h2>
            <p>Ajoutez une premiere evaluation pour ce cours.</p>
            <a class="button-link" href="{{ route('trainer.courses.evaluations.create', $cours) }}">Creer</a>
        </section>
    @else
        <section class="course-grid">
            @foreach ($evaluations as $evaluation)
                <article class="course-card">
                    <div class="card-header-line">
                        <span class="badge">{{ $evaluation->type_evaluation->value }}</span>
                        <span>{{ $evaluation->questions_count }} question(s)</span>
                    </div>
                    <h2><a href="{{ route('trainer.evaluations.edit', $evaluation) }}">{{ $evaluation->titre }}</a></h2>
                    <dl class="meta-grid">
                        <div><dt>Seuil</dt><dd>{{ $evaluation->seuil_reussite }}</dd></div>
                        <div><dt>Score max</dt><dd>{{ $evaluation->score_max }}</dd></div>
                    </dl>
                    <div class="inline-actions">
                        <a href="{{ route('trainer.evaluations.edit', $evaluation) }}">Modifier</a>
                        <form method="POST" action="{{ route('trainer.evaluations.destroy', $evaluation) }}" data-confirm="Supprimer cette evaluation ?">
                            @csrf
                            @method('DELETE')
                            <button class="danger-button" type="submit">Supprimer</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>

        {{ $evaluations->links() }}
    @endif
@endsection
