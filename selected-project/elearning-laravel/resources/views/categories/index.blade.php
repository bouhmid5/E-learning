@extends('layouts.app')

@section('content')
    <section class="page-heading">
        <h1>Catégories</h1>
    </section>

    @if ($categories->isEmpty())
        <section class="empty-state">
            <h2>Aucune catégorie disponible</h2>
            <p>Le catalogue sera organisé dès que des catégories seront ajoutées.</p>
        </section>
    @else
        <section class="category-list">
            @foreach ($categories as $categorie)
                <article class="course-card">
                    <h2><a href="{{ route('categories.courses', $categorie) }}">{{ $categorie->nom }}</a></h2>
                    <p>{{ $categorie->description }}</p>
                    <p>{{ $categorie->cours_count }} cours publié(s)</p>
                </article>
            @endforeach
        </section>
    @endif
@endsection

