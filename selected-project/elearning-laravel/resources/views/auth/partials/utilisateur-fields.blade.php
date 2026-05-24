<div class="form-grid">
    <label>
        Nom
        <input type="text" name="nom" value="{{ old('nom') }}" required autocomplete="family-name">
    </label>

    <label>
        Prenom
        <input type="text" name="prenom" value="{{ old('prenom') }}" required autocomplete="given-name">
    </label>
</div>

<label>
    Email
    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
</label>

<label>
    Telephone
    <input type="text" name="telephone" value="{{ old('telephone') }}" autocomplete="tel">
</label>

<div class="form-grid">
    <label>
        Mot de passe
        <input type="password" name="password" required autocomplete="new-password">
    </label>

    <label>
        Confirmation
        <input type="password" name="password_confirmation" required autocomplete="new-password">
    </label>
</div>
