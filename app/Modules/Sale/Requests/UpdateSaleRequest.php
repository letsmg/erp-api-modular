<?php

namespace App\Modules\Sale\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStaff();
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'sale_date' => ['nullable', 'date'],
            'status' => ['required', 'string', Rule::in(['pending', 'paid', 'canceled'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
