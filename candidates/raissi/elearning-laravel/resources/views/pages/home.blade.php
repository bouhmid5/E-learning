@extends('layouts.app')

@section('title', 'Formini')
@section('page_class', 'landing-page')

@section('content')
    <section class="landing-hero">
        <div class="landing-hero__content">
            <p class="eyebrow">Formini</p>
            <h1>Apprendre, enseigner et valider les competences avec clarte</h1>
            <p>Formini organise les cours publies, les parcours candidats, les evaluations et les validations administratives dans une interface simple et lisible.</p>
            <div class="hero-actions">
                <a href="{{ route('courses.index') }}" class="button-link">Explorer le catalogue</a>
                <a href="{{ route('register.candidate') }}" class="button-link button-link-soft">Creer un compte</a>
                <a href="{{ route('login') }}" class="hero-text-link">Connexion</a>
            </div>
        </div>
    </section>

    <section class="landing-section">
        <div class="landing-section__head">
            <div>
                <p class="section-kicker eyebrow">Catalogue</p>
                <h2>Des formations publiees et faciles a comparer</h2>
            </div>
            <p>Les candidats trouvent les cours par categorie, niveau, langue, duree et prix. Les contenus non valides restent invisibles au public.</p>
        </div>
        <div class="landing-grid">
            <article class="landing-card">
                <span>01</span>
                <h3>Recherche claire</h3>
                <p>Filtres et tri aident a trouver rapidement un cours adapte.</p>
            </article>
            <article class="landing-card">
                <span>02</span>
                <h3>Parcours structures</h3>
                <p>Chaque cours regroupe lecons, ressources, evaluations et progression.</p>
            </article>
            <article class="landing-card">
                <span>03</span>
                <h3>Certification</h3>
                <p>Les certificats sont generes lorsque les conditions de reussite sont remplies.</p>
            </article>
        </div>
    </section>

    <section class="landing-band">
        <div>
            <p class="eyebrow">Espaces roles</p>
            <h2>Un tableau de bord pour chaque profil</h2>
            <p>Candidat, formateur et administrateur disposent uniquement des actions utiles a leur role.</p>
        </div>
        <div class="landing-stats" aria-label="Espaces disponibles">
            <div><strong>3</strong><span>roles proteges</span></div>
            <div><strong>100%</strong><span>Blade Laravel</span></div>
            <div><strong>0</strong><span>frontend separe</span></div>
        </div>
    </section>
@endsection
