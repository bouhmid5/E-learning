<?php

namespace App\Http\Requests\Trainer;

use App\Enums\TypeRessource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_map(fn (TypeRessource $type) => $type->value, TypeRessource::cases()))],
            'url' => ['required_if:type,LIEN', 'nullable', 'url', 'max:255'],
            'fichier' => ['nullable', 'file', 'max:20480'],
            'ordre' => ['required', 'integer', 'min:1'],
            'telechargeable' => ['nullable', 'boolean'],
        ];
    }
}
