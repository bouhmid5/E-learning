@extends('layouts.app')

@section('content')
    <section class="auth-panel">
        <h1>Connexion</h1>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <label>
                Type de compte
                <select name="account_type">
                    <option value="utilisateur" @selected(old('account_type') !== 'admin')>Utilisateur</option>
                    <option value="admin" @selected(old('account_type') === 'admin')>Administrateur</option>
                </select>
            </label>

            <label>
                Email
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror

            <label>
                Mot de passe
                <input type="password" name="password" required>
            </label>

            <label class="checkbox-line">
                <input type="checkbox" name="remember" value="1">
                Se souvenir de moi
            </label>

            <button type="submit">Se connecter</button>
        </form>
    </section>
@endsection

