@extends('layouts.app')

@section('title', 'Inscription candidat Formini')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <x-application-logo />
            <p class="eyebrow">Compte candidat Formini</p>
            <h1>Creez votre espace d'apprentissage</h1>
            <p>Suivez vos cours, vos evaluations et vos certificats depuis Formini.</p>
        </div>

        <div class="auth-panel">
            <div class="auth-heading">
                <h2>Inscription candidat</h2>
                <p class="muted">Tous les champs marques d'un asterisque sont obligatoires.</p>
            </div>

            <form method="POST" action="{{ route('register.candidate.store') }}">
                @csrf

                @include('auth.partials.utilisateur-fields')

                <div class="form-grid">
                    <label>
                        Niveau
                        <input type="text" name="niveau" value="{{ old('niveau') }}" placeholder="debutant, intermediaire...">
                        <x-form-error field="niveau" />
                    </label>

                    <label>
                        Objectif d'apprentissage
                        <textarea name="objectif_apprentissage" placeholder="Votre objectif principal">{{ old('objectif_apprentissage') }}</textarea>
                        <x-form-error field="objectif_apprentissage" />
                    </label>
                </div>

                <x-button>Creer le compte candidat</x-button>
            </form>

            <div class="auth-links">
                <a href="{{ route('login') }}">Retour a la connexion</a>
            </div>
        </div>
    </section>
@endsection
