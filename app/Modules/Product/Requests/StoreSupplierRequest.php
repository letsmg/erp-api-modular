<?php

namespace App\Modules\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStaff();
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:150'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:suppliers,cnpj'],
            'state_registration' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:150'],
            'neighborhood' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:10'],
            'state' => ['required', 'string', 'size:2'],
            'email' => ['required', 'email', 'unique:suppliers,email'],
            'contact_name_1' => ['required', 'string', 'max:100'],
            'phone_1' => ['required', 'string', 'max:20'],
            'contact_name_2' => ['nullable', 'string', 'max:100'],
            'phone_2' => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
