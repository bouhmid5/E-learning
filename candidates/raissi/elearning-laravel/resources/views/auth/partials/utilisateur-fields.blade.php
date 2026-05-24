<div class="form-grid">
    <label class="required">
        Nom
        <input type="text" name="nom" value="{{ old('nom') }}" required autocomplete="family-name">
        <x-form-error field="nom" />
    </label>

    <label class="required">
        Prenom
        <input type="text" name="prenom" value="{{ old('prenom') }}" required autocomplete="given-name">
        <x-form-error field="prenom" />
    </label>
</div>

<label class="required">
    Email
    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
    <x-form-error field="email" />
</label>

<label>
    Telephone
    <input type="text" name="telephone" value="{{ old('telephone') }}" autocomplete="tel">
    <x-form-error field="telephone" />
</label>

<div class="form-grid">
    <label class="required">
        Mot de passe
        <input type="password" name="password" required autocomplete="new-password">
        <x-form-error field="password" />
    </label>

    <label class="required">
        Confirmation du mot de passe
        <input type="password" name="password_confirmation" required autocomplete="new-password">
    </label>
</div>
