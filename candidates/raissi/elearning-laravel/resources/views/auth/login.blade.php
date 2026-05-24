@extends('layouts.app')

@section('title', 'Connexion Formini')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <x-application-logo />
            <p class="eyebrow">Connexion Formini</p>
            <h1>Accedez a votre espace Formini</h1>
            <p>Connectez-vous comme candidat, formateur ou administrateur avec votre compte active.</p>
        </div>

        <div class="auth-panel">
            <div class="auth-heading">
                <h2>Connexion</h2>
                <p class="muted">Renseignez vos identifiants Formini.</p>
            </div>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <label class="required">
                    Type de compte
                    <select name="account_type">
                        <option value="utilisateur" @selected(old('account_type') !== 'admin')>Candidat ou formateur</option>
                        <option value="admin" @selected(old('account_type') === 'admin')>Administrateur</option>
                    </select>
                    <x-form-error field="account_type" />
                </label>

                <label class="required">
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                    <x-form-error field="email" />
                </label>

                <label class="required">
                    Mot de passe
                    <input type="password" name="password" required autocomplete="current-password">
                    <x-form-error field="password" />
                </label>

                <label class="checkbox-line">
                    <input type="checkbox" name="remember" value="1">
                    Se souvenir de moi
                </label>

                <x-button>Se connecter</x-button>
            </form>

            <div class="auth-links">
                <a href="{{ route('register.candidate') }}">Creer un compte candidat</a>
                <a href="{{ route('register.trainer') }}">Demander un compte formateur</a>
            </div>
        </div>
    </section>
@endsection
