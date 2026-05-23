@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Categories</h1>
        <p><a href="{{ route('admin.categories.create') }}">Creer une categorie</a></p>
    </section>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <section>
        @foreach ($categories as $categorie)
            <article class="course-card">
                <h2>{{ $categorie->nom }}</h2>
                <p>Parent: {{ $categorie->parent?->nom ?? 'Aucun' }}</p>
                <p>{{ $categorie->description }}</p>
                <p><a href="{{ route('admin.categories.edit', $categorie) }}">Modifier</a></p>
                <form method="POST" action="{{ route('admin.categories.destroy', $categorie) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Supprimer</button>
                </form>
            </article>
        @endforeach
    </section>

    {{ $categories->links() }}
@endsection
