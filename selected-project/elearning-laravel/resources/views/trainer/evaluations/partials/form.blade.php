<label>
    Titre
    <input type="text" name="titre" value="{{ old('titre', $evaluation?->titre) }}" required>
</label>

<label>
    Description
    <textarea name="description">{{ old('description', $evaluation?->description) }}</textarea>
</label>

<label>
    Type
    <select name="type_evaluation">
        @foreach (\App\Enums\TypeEvaluation::cases() as $type)
            <option value="{{ $type->value }}" @selected(old('type_evaluation', $evaluation?->type_evaluation?->value) === $type->value)>
                {{ $type->value }}
            </option>
        @endforeach
    </select>
</label>

<label>
    Score maximum
    <input type="number" name="score_max" value="{{ old('score_max', $evaluation?->score_max ?? 100) }}" min="0" step="0.01" required>
</label>

<label>
    Seuil de réussite
    <input type="number" name="seuil_reussite" value="{{ old('seuil_reussite', $evaluation?->seuil_reussite ?? 50) }}" min="0" step="0.01" required>
</label>

<label>
    Ordre
    <input type="number" name="ordre" value="{{ old('ordre', $evaluation?->ordre ?? 1) }}" min="1" required>
</label>

<label class="checkbox-line">
    <input type="checkbox" name="actif" value="1" @checked(old('actif', $evaluation?->actif ?? true))>
    Active
</label>

