<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categorie_id' => ['required', 'uuid', 'exists:categories,id'],
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'niveau' => ['nullable', 'string', 'max:255'],
            'langue' => ['required', 'string', 'max:20'],
            'prix' => ['required', 'numeric', 'min:0'],
            'duree_estimee' => ['nullable', 'integer', 'min:0'],
            'image_url' => ['nullable', 'string', 'max:255'],
        ];
    }
}

