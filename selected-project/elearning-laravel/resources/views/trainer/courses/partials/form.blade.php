<div class="form-grid">
    <label>
        Categorie
        <select name="categorie_id" required>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id }}" @selected(old('categorie_id', $cours?->categorie_id) === $categorie->id)>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Titre
        <input type="text" name="titre" value="{{ old('titre', $cours?->titre) }}" required>
    </label>
</div>

<label>
    Description
    <textarea name="description">{{ old('description', $cours?->description) }}</textarea>
</label>

<div class="form-grid">
    <label>
        Niveau
        <input type="text" name="niveau" value="{{ old('niveau', $cours?->niveau) }}" placeholder="debutant, intermediaire...">
    </label>

    <label>
        Langue
        <input type="text" name="langue" value="{{ old('langue', $cours?->langue ?? 'fr') }}" required>
    </label>

    <label>
        Prix
        <input type="number" name="prix" value="{{ old('prix', $cours?->prix ?? 0) }}" min="0" step="0.01" required>
    </label>

    <label>
        Duree estimee
        <input type="number" name="duree_estimee" value="{{ old('duree_estimee', $cours?->duree_estimee) }}" min="0">
    </label>
</div>

<label>
    Image URL
    <input type="text" name="image_url" value="{{ old('image_url', $cours?->image_url) }}" placeholder="https://...">
</label>
