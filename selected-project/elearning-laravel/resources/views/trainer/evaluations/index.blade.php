@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Evaluations - {{ $cours->titre }}</h1>
        <p><a href="{{ route('trainer.courses.evaluations.create', $cours) }}">Creer une evaluation</a></p>
    </section>

    @if ($evaluations->isEmpty())
        <section class="empty-state">
            <h2>Aucune evaluation</h2>
            <p>Ajoutez une premiere evaluation pour ce cours.</p>
        </section>
    @else
        <section class="course-grid">
            @foreach ($evaluations as $evaluation)
                <article class="course-card">
                    <h2><a href="{{ route('trainer.evaluations.edit', $evaluation) }}">{{ $evaluation->titre }}</a></h2>
                    <p>{{ $evaluation->type_evaluation->value }} - seuil {{ $evaluation->seuil_reussite }} - {{ $evaluation->questions_count }} question(s)</p>
                    <form method="POST" action="{{ route('trainer.evaluations.destroy', $evaluation) }}" data-confirm="Supprimer cette evaluation ?">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Supprimer</button>
                    </form>
                </article>
            @endforeach
        </section>

        {{ $evaluations->links() }}
    @endif
@endsection
