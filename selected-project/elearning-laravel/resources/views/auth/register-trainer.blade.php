@extends('layouts.app')

@section('title', 'Inscription formateur')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Espace formateur</p>
            <h1>Demandez votre accès formateur</h1>
            <p>Le compte formateur est créé en attente de validation avant publication de cours.</p>
        </div>

        <div class="auth-panel">
            <form method="POST" action="{{ route('register.trainer.store') }}">
                @csrf

                @include('auth.partials.utilisateur-fields')

                <label>
                    Spécialité
                    <input type="text" name="specialite" value="{{ old('specialite') }}">
                </label>

                <label>
                    Biographie
                    <textarea name="biographie">{{ old('biographie') }}</textarea>
                </label>

                <button type="submit">Demander l'inscription formateur</button>
            </form>
        </div>
    </section>
@endsection
