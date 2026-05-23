@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Cours en attente</h1>
    </section>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <section>
        @forelse ($courses as $cours)
            <article class="course-card">
                <h2>{{ $cours->titre }}</h2>
                <p>{{ $cours->categorie?->nom }} · {{ $cours->formateur?->utilisateur?->email }}</p>
                <p>{{ $cours->description }}</p>
                <form method="POST" action="{{ route('admin.courses.validate', $cours) }}">
                    @csrf
                    <button type="submit">Valider et publier</button>
                </form>
                <form method="POST" action="{{ route('admin.courses.reject', $cours) }}">
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
