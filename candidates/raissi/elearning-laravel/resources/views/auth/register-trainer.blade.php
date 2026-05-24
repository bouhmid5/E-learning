@extends('layouts.app')

@section('title', 'Inscription formateur Formini')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <x-application-logo />
            <p class="eyebrow">Compte formateur Formini</p>
            <h1>Demandez votre acces formateur</h1>
            <p>Votre compte formateur sera examine par un administrateur avant activation.</p>
        </div>

        <div class="auth-panel">
            <div class="auth-heading">
                <h2>Inscription formateur</h2>
                <p class="muted">Tous les champs marques d'un asterisque sont obligatoires.</p>
            </div>

            <form method="POST" action="{{ route('register.trainer.store') }}">
                @csrf

                @include('auth.partials.utilisateur-fields')

                <div class="form-grid">
                    <label>
                        Specialite
                        <input type="text" name="specialite" value="{{ old('specialite') }}" placeholder="Laravel, SQL, UX...">
                        <x-form-error field="specialite" />
                    </label>

                    <label>
                        Biographie
                        <textarea name="biographie" placeholder="Votre experience de formation">{{ old('biographie') }}</textarea>
                        <x-form-error field="biographie" />
                    </label>
                </div>

                <div class="upload-placeholder">
                    <strong>Justificatifs</strong>
                    <p class="muted">Diplomes et certificats seront rattaches au workflow de validation formateur. Le depot dedie n'est pas encore expose par une route backend.</p>
                    <input type="file" name="justificatifs[]" multiple disabled>
                </div>

                <x-button>Envoyer la demande</x-button>
            </form>

            <div class="auth-links">
                <a href="{{ route('login') }}">Retour a la connexion</a>
            </div>
        </div>
    </section>
@endsection
