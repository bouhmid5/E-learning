<?php

namespace App\Http\Requests\Catalogue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'uuid', 'exists:categories,id'],
            'niveau' => ['nullable', 'string', 'max:255'],
            'langue' => ['nullable', 'string', 'max:20'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'min_duration' => ['nullable', 'integer', 'min:0'],
            'max_duration' => ['nullable', 'integer', 'min:0', 'gte:min_duration'],
            'trainer' => ['nullable', 'uuid', 'exists:formateurs,id'],
            'keyword' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', Rule::in(['date_publication', 'titre', 'prix', 'duree_estimee'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }

    public function filters(): array
    {
        return $this->validated();
    }
}

