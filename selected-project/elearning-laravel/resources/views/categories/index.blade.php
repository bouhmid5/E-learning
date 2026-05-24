@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Catalogue</p>
        <h1>Categories</h1>
        <p>Accedez rapidement aux cours publies par domaine.</p>
    </section>

    @if ($categories->isEmpty())
        <section class="empty-state">
            <h2>Aucune categorie disponible</h2>
            <p>Le catalogue sera organise des que des categories seront ajoutees.</p>
        </section>
    @else
        <section class="category-list">
            @foreach ($categories as $categorie)
                <article class="course-card">
                    <h2><a href="{{ route('categories.courses', $categorie) }}">{{ $categorie->nom }}</a></h2>
                    <p>{{ $categorie->description }}</p>
                    <p>{{ $categorie->cours_count }} cours publie(s)</p>
                </article>
            @endforeach
        </section>
    @endif
@endsection
