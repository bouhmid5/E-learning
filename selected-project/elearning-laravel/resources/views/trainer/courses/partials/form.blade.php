<label>
    Catégorie
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

<label>
    Description
    <textarea name="description">{{ old('description', $cours?->description) }}</textarea>
</label>

<label>
    Niveau
    <input type="text" name="niveau" value="{{ old('niveau', $cours?->niveau) }}">
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
    Durée estimée
    <input type="number" name="duree_estimee" value="{{ old('duree_estimee', $cours?->duree_estimee) }}" min="0">
</label>

<label>
    Image URL
    <input type="text" name="image_url" value="{{ old('image_url', $cours?->image_url) }}">
</label>

