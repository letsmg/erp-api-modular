<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStaff();
    }

    public function rules(): array
    {
        return [
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:50'],
            'collection' => ['nullable', 'string', 'max:100'],
            'gender' => ['required', 'string', 'max:50'],
            'barcode' => ['nullable', 'string', 'max:100'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'promo_start_at' => ['nullable', 'date'],
            'promo_end_at' => ['nullable', 'date', 'after_or_equal:promo_start_at'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'weight' => ['required', 'numeric', 'min:0'],
            'width' => ['required', 'numeric', 'min:0'],
            'height' => ['required', 'numeric', 'min:0'],
            'length' => ['required', 'numeric', 'min:1'],
            'free_shipping' => ['sometimes', 'boolean'],
            'slug' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['required', 'string', 'max:70'],
            'meta_description' => ['required', 'string', 'max:160'],
            'meta_keywords' => ['required', 'string'],
            'h1' => ['required', 'string'],
            'text1' => ['required', 'string'],
            'h2' => ['nullable', 'string'],
            'text2' => ['nullable', 'string'],
            'schema_markup' => ['nullable', 'string'],
            'google_tag_manager' => ['nullable', 'string'],
            'existing_images' => ['nullable', 'array'],
            'existing_images.*' => ['integer', 'exists:product_images,id'],
            'new_images' => ['nullable', 'array', 'max:6'],
            'new_images.*' => ['file'],
        ];
    }

    protected function passedValidation(): void
    {
        $existing = collect($this->input('existing_images', []))
            ->map(fn ($value) => is_array($value) ? ($value['id'] ?? null) : $value)
            ->filter()
            ->values()
            ->all();

        $this->merge(['existing_images' => $existing]);
    }
}
