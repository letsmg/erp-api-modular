<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStaff();
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category?->id)],
            'parent_id' => ['nullable', 'exists:categories,id', Rule::notIn([$category?->id])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
