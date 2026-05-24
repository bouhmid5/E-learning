@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <section class="page-heading dashboard-hero">
        <div>
            <p class="eyebrow">Espace personnel</p>
            <h1>Tableau de bord Formini</h1>
            <p>Choisissez l'espace adapte a votre role ou continuez vers le catalogue public.</p>
        </div>
        <a class="button-link" href="{{ route('courses.index') }}">Parcourir les cours</a>
    </section>
@endsection
