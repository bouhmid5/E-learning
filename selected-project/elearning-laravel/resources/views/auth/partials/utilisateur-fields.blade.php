<label>
    Nom
    <input type="text" name="nom" value="{{ old('nom') }}" required autocomplete="family-name">
</label>
@error('nom')
    <p class="field-error">{{ $message }}</p>
@enderror

<label>
    Prénom
    <input type="text" name="prenom" value="{{ old('prenom') }}" required autocomplete="given-name">
</label>
@error('prenom')
    <p class="field-error">{{ $message }}</p>
@enderror

<label>
    Email
    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
</label>
@error('email')
    <p class="field-error">{{ $message }}</p>
@enderror

<label>
    Téléphone
    <input type="text" name="telephone" value="{{ old('telephone') }}" autocomplete="tel">
</label>

<label>
    Mot de passe
    <input type="password" name="password" required autocomplete="new-password">
</label>
@error('password')
    <p class="field-error">{{ $message }}</p>
@enderror

<label>
    Confirmer le mot de passe
    <input type="password" name="password_confirmation" required autocomplete="new-password">
</label>
