@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Mes cours</h1>
        <p><a href="{{ route('trainer.courses.create') }}">Créer un cours</a></p>
    </section>

    @if ($courses->isEmpty())
        <section class="empty-state">
            <h2>Aucun cours</h2>
            <p>Créez un premier brouillon pour commencer.</p>
        </section>
    @else
        <section class="course-grid">
            @foreach ($courses as $cours)
                <article class="course-card">
                    <h2><a href="{{ route('trainer.courses.show', $cours) }}">{{ $cours->titre }}</a></h2>
                    <p>{{ $cours->categorie?->nom }} · {{ $cours->statut->value }}</p>
                    <p>{{ $cours->description }}</p>
                </article>
            @endforeach
        </section>

        {{ $courses->links() }}
    @endif
@endsection

