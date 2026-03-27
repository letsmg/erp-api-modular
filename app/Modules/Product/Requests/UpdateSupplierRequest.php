<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStaff();
    }

    public function rules(): array
    {
        $supplier = $this->route('supplier');

        return [
            'company_name' => ['required', 'string', 'max:150'],
            'cnpj' => ['required', 'string', 'max:18', Rule::unique('suppliers', 'cnpj')->ignore($supplier?->id)],
            'state_registration' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:150'],
            'neighborhood' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:10'],
            'state' => ['required', 'string', 'size:2'],
            'email' => ['required', 'email', Rule::unique('suppliers', 'email')->ignore($supplier?->id)],
            'contact_name_1' => ['required', 'string', 'max:100'],
            'phone_1' => ['required', 'string', 'max:20'],
            'contact_name_2' => ['nullable', 'string', 'max:100'],
            'phone_2' => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
