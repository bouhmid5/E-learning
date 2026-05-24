@extends('layouts.app')

@section('title', 'Plateforme E-learning')
@section('page_class', 'landing-page')

@section('content')
    <section class="landing-hero">
        <div class="landing-hero__content">
            <p class="eyebrow">Plateforme E-learning</p>
            <h1>Academie digitale pour apprendre et enseigner</h1>
            <p>Des parcours guides, des formateurs valides et une progression claire pour transformer chaque session en competence mesurable.</p>
            <div class="hero-actions">
                <a href="{{ route('register.candidate') }}" class="button-link button-link-accent">Commencer maintenant</a>
                <a href="{{ route('login') }}" class="button-link button-link-soft">Connexion</a>
                <a href="{{ route('courses.index') }}" class="hero-text-link">Explorer le catalogue</a>
            </div>
        </div>
    </section>

    <section id="formations" class="landing-section">
        <div class="section-kicker">Formations</div>
        <div class="landing-section__head">
            <h2>Un catalogue pense pour avancer sans perdre le fil</h2>
            <p>Les cours publies regroupent objectifs, lecons, ressources et suivi de progression dans un parcours lisible.</p>
        </div>
        <div class="landing-grid">
            <article class="landing-card">
                <span>01</span>
                <h3>Parcours structures</h3>
                <p>Chaque cours s'organise en lecons ordonnees avec supports et durees estimees.</p>
            </article>
            <article class="landing-card">
                <span>02</span>
                <h3>Recherche rapide</h3>
                <p>Filtres par categorie, niveau, langue, prix et formateur pour trouver le bon contenu.</p>
            </article>
            <article class="landing-card">
                <span>03</span>
                <h3>Progression visible</h3>
                <p>Les candidats suivent leurs lecons terminees et visualisent leur avancement en temps reel.</p>
            </article>
        </div>
    </section>

    <section id="experience" class="landing-band">
        <div>
            <p class="section-kicker">Experience</p>
            <h2>Un espace clair pour chaque profil</h2>
            <p>Candidat, formateur ou administrateur: chacun retrouve ses actions essentielles des la connexion.</p>
        </div>
        <div class="landing-stats" aria-label="Indicateurs plateforme">
            <div><strong>3</strong><span>espaces dedies</span></div>
            <div><strong>100%</strong><span>suivi progression</span></div>
            <div><strong>24/7</strong><span>acces catalogue</span></div>
        </div>
    </section>

    <section id="communaute" class="landing-section landing-section-split">
        <div>
            <p class="section-kicker">Communaute</p>
            <h2>Des formateurs valides, des apprenants mieux accompagnes</h2>
            <p>Le workflow d'administration controle les comptes formateurs, les justificatifs et les cours avant publication.</p>
        </div>
        <div class="landing-checklist">
            <p>Validation des formateurs</p>
            <p>Publication des cours apres controle</p>
            <p>Inscriptions candidates protegees</p>
            <p>Ressources accessibles selon l'inscription</p>
        </div>
    </section>
@endsection
