@extends('layouts.app')

@section('title', 'Plateforme E-learning')

@section('content')
    <section class="home-hero">
        <div>
            <p class="eyebrow">Plateforme E-learning</p>
            <h1>Apprendre, enseigner et valider les compétences en ligne</h1>
            <p>Un espace Laravel Blade pour les candidats, formateurs et administrateurs.</p>
            <div class="hero-actions">
                <a href="{{ route('courses.index') }}" class="button-link">Explorer le catalogue</a>
                <a href="{{ route('register.candidate') }}">Créer un compte candidat</a>
            </div>
        </div>
    </section>
@endsection
