@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <section class="page-heading">
        <p class="eyebrow">Espace personnel</p>
        <h1>Tableau de bord</h1>
        <p>Choisissez l'espace adapté à votre rôle.</p>
    </section>

    <section class="action-grid">
        <a class="action-card" href="{{ route('courses.index') }}">
            <span>Catalogue</span>
            <strong>Parcourir les cours publiés</strong>
        </a>
    </section>
@endsection
