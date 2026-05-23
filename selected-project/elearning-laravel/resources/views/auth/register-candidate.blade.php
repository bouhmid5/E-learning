@extends('layouts.app')

@section('content')
    <section class="auth-panel">
        <h1>Inscription candidat</h1>

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
    </section>
@endsection

