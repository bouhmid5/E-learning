@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <section class="auth-shell">
        <div class="auth-copy">
            <p class="eyebrow">Acces plateforme</p>
            <h1>Connectez-vous a votre espace</h1>
            <p>Un seul formulaire pour les candidats, formateurs et administrateurs, avec des espaces proteges par role.</p>
        </div>

        <div class="auth-panel">
            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <label>
                    Type de compte
                    <select name="account_type">
                        <option value="utilisateur" @selected(old('account_type') !== 'admin')>Candidat ou formateur</option>
                        <option value="admin" @selected(old('account_type') === 'admin')>Administrateur</option>
                    </select>
                </label>

                <label>
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                </label>

                <label>
                    Mot de passe
                    <input type="password" name="password" required autocomplete="current-password">
                </label>

                <label class="checkbox-line">
                    <input type="checkbox" name="remember" value="1">
                    Se souvenir de moi
                </label>

                <button type="submit">Se connecter</button>
            </form>

            <div class="auth-links">
                <a href="{{ route('register.candidate') }}">Creer un compte candidat</a>
                <a href="{{ route('register.trainer') }}">Demander un compte formateur</a>
            </div>
        </div>
    </section>
@endsection
