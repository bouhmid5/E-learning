@extends('layouts.app')

@section('title', 'Inscription candidat')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Espace candidat</p>
            <h1>Creez votre compte d'apprentissage</h1>
            <p>Suivez vos cours, vos evaluations, votre progression et vos certificats depuis un espace clair.</p>
        </div>

        <div class="auth-panel">
            <form method="POST" action="{{ route('register.candidate.store') }}">
                @csrf

                @include('auth.partials.utilisateur-fields')

                <div class="form-grid">
                    <label>
                        Niveau
                        <input type="text" name="niveau" value="{{ old('niveau') }}" placeholder="debutant, intermediaire...">
                    </label>

                    <label>
                        Objectif d'apprentissage
                        <textarea name="objectif_apprentissage" placeholder="Votre objectif principal">{{ old('objectif_apprentissage') }}</textarea>
                    </label>
                </div>

                <button type="submit">Creer le compte candidat</button>
            </form>
        </div>
    </section>
@endsection
