<label>
    Parent
    <select name="parent_id">
        <option value="">Aucun</option>
        @foreach ($parents as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id', $categorie->parent_id) === $parent->id)>
                {{ $parent->nom }}
            </option>
        @endforeach
    </select>
</label>

<label>
    Nom
    <input type="text" name="nom" value="{{ old('nom', $categorie->nom) }}" required>
</label>

<label>
    Description
    <textarea name="description">{{ old('description', $categorie->description) }}</textarea>
</label>
