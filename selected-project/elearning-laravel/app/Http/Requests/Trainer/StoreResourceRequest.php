<?php

namespace App\Http\Requests\Trainer;

use App\Enums\TypeRessource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResourceRequest extends FormRequest
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
            'url' => ['required_if:type,LIEN', 'nullable', 'url', 'starts_with:http://,https://', 'max:255'],
            'fichier' => ['required_unless:type,LIEN', 'nullable', 'file', 'mimes:pdf,mp4,webm,doc,docx,ppt,pptx,txt,zip', 'max:20480'],
            'ordre' => ['required', 'integer', 'min:1'],
            'telechargeable' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'url.required_if' => 'Une URL est requise pour une ressource de type lien.',
            'url.starts_with' => 'Les liens doivent commencer par http:// ou https://.',
            'fichier.required_unless' => 'Un fichier est requis pour les ressources document ou video.',
            'fichier.mimes' => 'Le fichier doit etre un PDF, document, presentation, video web, texte ou archive ZIP.',
            'fichier.max' => 'Le fichier ne doit pas depasser 20 Mo.',
        ];
    }
}
