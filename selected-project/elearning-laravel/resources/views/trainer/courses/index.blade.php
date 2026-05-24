@extends('layouts.app')

@section('title', 'Mes cours')

@section('content')
    <section class="page-heading split-heading">
        <div>
            <p class="eyebrow">Studio formateur</p>
            <h1>Mes cours</h1>
            <p>Gerez vos brouillons, cours rejetes et contenus en attente de validation.</p>
        </div>
        <a class="button-link" href="{{ route('trainer.courses.create') }}">Creer un cours</a>
    </section>

    @if ($courses->isEmpty())
        <section class="empty-state">
            <h2>Aucun cours</h2>
            <p>Creez un premier brouillon pour commencer.</p>
            <a class="button-link" href="{{ route('trainer.courses.create') }}">Nouveau brouillon</a>
        </section>
    @else
        <section class="course-grid">
            @foreach ($courses as $cours)
                <article class="course-card">
                    <div class="card-header-line">
                        <span class="badge">{{ $cours->statut->value }}</span>
                        <span>{{ $cours->categorie?->nom ?? 'Sans categorie' }}</span>
                    </div>
                    <h2><a href="{{ route('trainer.courses.show', $cours) }}">{{ $cours->titre }}</a></h2>
                    <p>{{ \Illuminate\Support\Str::limit($cours->description, 150) }}</p>
                    <dl class="meta-grid">
                        <div><dt>Niveau</dt><dd>{{ $cours->niveau }}</dd></div>
                        <div><dt>Duree</dt><dd>{{ $cours->duree_estimee }} min</dd></div>
                    </dl>
                    <a class="card-action" href="{{ route('trainer.courses.show', $cours) }}">Ouvrir l'atelier</a>
                </article>
            @endforeach
        </section>

        {{ $courses->links() }}
    @endif
@endsection
