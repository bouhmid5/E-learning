@extends('layouts.app')

@section('content')
    <section class="auth-panel">
        <h1>Inscription formateur</h1>

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
    </section>
@endsection

