@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Cours en attente</h1>
    </section>

    <section>
        @forelse ($courses as $cours)
            <article class="course-card">
                <h2>{{ $cours->titre }}</h2>
                <p>{{ $cours->categorie?->nom }} - {{ $cours->formateur?->utilisateur?->email }}</p>
                <p>{{ $cours->description }}</p>
                <form method="POST" action="{{ route('admin.courses.validate', $cours) }}" data-confirm="Publier ce cours ?">
                    @csrf
                    <button type="submit">Valider et publier</button>
                </form>
                <form method="POST" action="{{ route('admin.courses.reject', $cours) }}" data-confirm="Rejeter ce cours ?">
                    @csrf
                    <textarea name="motif_rejet" required placeholder="Motif du rejet"></textarea>
                    <button type="submit">Rejeter</button>
                </form>
            </article>
        @empty
            <p>Aucun cours en attente.</p>
        @endforelse
    </section>

    {{ $courses->links() }}
@endsection
