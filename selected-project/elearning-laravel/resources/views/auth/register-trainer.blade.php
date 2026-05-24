@extends('layouts.app')

@section('title', 'Inscription formateur')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Espace formateur</p>
            <h1>Demandez votre acces formateur</h1>
            <p>Votre demande reste en attente jusqu'a validation par un administrateur.</p>
        </div>

        <div class="auth-panel">
            <form method="POST" action="{{ route('register.trainer.store') }}">
                @csrf

                @include('auth.partials.utilisateur-fields')

                <div class="form-grid">
                    <label>
                        Specialite
                        <input type="text" name="specialite" value="{{ old('specialite') }}" placeholder="Laravel, SQL, UX...">
                    </label>

                    <label>
                        Biographie
                        <textarea name="biographie" placeholder="Votre experience de formation">{{ old('biographie') }}</textarea>
                    </label>
                </div>

                <button type="submit">Envoyer la demande</button>
            </form>
        </div>
    </section>
@endsection
