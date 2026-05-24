@extends('layouts.app')

@section('title', 'Inscription candidat')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Espace candidat</p>
            <h1>Créez votre compte d'apprentissage</h1>
            <p>Votre espace candidat vous permettra de suivre vos cours, évaluations et certificats.</p>
        </div>

        <div class="auth-panel">
            <form method="POST" action="{{ route('register.candidate.store') }}">
                @csrf

                @include('auth.partials.utilisateur-fields')

                <label>
                    Niveau
                    <input type="text" name="niveau" value="{{ old('niveau') }}">
                </label>

                <label>
                    Objectif d'apprentissage
                    <textarea name="objectif_apprentissage">{{ old('objectif_apprentissage') }}</textarea>
                </label>

                <button type="submit">Créer le compte candidat</button>
            </form>
        </div>
    </section>
@endsection
