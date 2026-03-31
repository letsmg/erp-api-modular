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

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'O campo fornecedor é obrigatório.',
            'supplier_id.exists' => 'O fornecedor selecionado não existe.',
            'category_id.required' => 'O campo categoria é obrigatório.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'description.required' => 'O campo descrição é obrigatório.',
            'description.max' => 'A descrição não pode ter mais que 255 caracteres.',
            'brand.required' => 'O campo marca é obrigatório.',
            'brand.max' => 'A marca não pode ter mais que 100 caracteres.',
            'model.required' => 'O campo modelo é obrigatório.',
            'model.max' => 'O modelo não pode ter mais que 100 caracteres.',
            'size.max' => 'O tamanho não pode ter mais que 50 caracteres.',
            'collection.max' => 'A coleção não pode ter mais que 100 caracteres.',
            'gender.required' => 'O campo gênero é obrigatório.',
            'gender.max' => 'O gênero não pode ter mais que 50 caracteres.',
            'barcode.max' => 'O código de barras não pode ter mais que 100 caracteres.',
            'cost_price.required' => 'O campo preço de custo é obrigatório.',
            'cost_price.numeric' => 'O preço de custo deve ser um número.',
            'cost_price.min' => 'O preço de custo deve ser maior ou igual a 0.',
            'sale_price.required' => 'O campo preço de venda é obrigatório.',
            'sale_price.numeric' => 'O preço de venda deve ser um número.',
            'sale_price.min' => 'O preço de venda deve ser maior ou igual a 0.',
            'promo_price.numeric' => 'O preço promocional deve ser um número.',
            'promo_price.min' => 'O preço promocional deve ser maior ou igual a 0.',
            'promo_start_at.date' => 'A data de início da promoção deve ser uma data válida.',
            'promo_end_at.date' => 'A data de término da promoção deve ser uma data válida.',
            'promo_end_at.after_or_equal' => 'A data de término deve ser posterior ou igual à data de início.',
            'stock_quantity.required' => 'O campo quantidade em estoque é obrigatório.',
            'stock_quantity.integer' => 'A quantidade em estoque deve ser um número inteiro.',
            'stock_quantity.min' => 'A quantidade em estoque deve ser maior ou igual a 0.',
            'weight.required' => 'O campo peso é obrigatório.',
            'weight.numeric' => 'O peso deve ser um número.',
            'weight.min' => 'O peso deve ser maior ou igual a 0.',
            'width.required' => 'O campo largura é obrigatório.',
            'width.numeric' => 'A largura deve ser um número.',
            'width.min' => 'A largura deve ser maior ou igual a 0.',
            'height.required' => 'O campo altura é obrigatório.',
            'height.numeric' => 'A altura deve ser um número.',
            'height.min' => 'A altura deve ser maior ou igual a 0.',
            'length.required' => 'O campo comprimento é obrigatório.',
            'length.numeric' => 'O comprimento deve ser um número.',
            'length.min' => 'O comprimento deve ser maior que 0.',
            'slug.max' => 'O slug não pode ter mais que 255 caracteres.',
            'meta_title.required' => 'O campo título SEO é obrigatório.',
            'meta_title.max' => 'O título SEO não pode ter mais que 70 caracteres.',
            'meta_description.required' => 'O campo descrição SEO é obrigatório.',
            'meta_description.max' => 'A descrição SEO não pode ter mais que 160 caracteres.',
            'meta_keywords.required' => 'O campo palavras-chave SEO é obrigatório.',
            'h1.required' => 'O campo título H1 é obrigatório.',
            'text1.required' => 'O campo texto de apresentação é obrigatório.',
            'existing_images.*.exists' => 'Uma das imagens existentes não foi encontrada.',
            'new_images.max' => 'É permitido no máximo 6 imagens no total.',
            'new_images.*.file' => 'O arquivo deve ser uma imagem válida.',
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
