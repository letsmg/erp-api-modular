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

    public function messages(): array
    {
        return [
            'company_name.required' => 'O campo razão social é obrigatório.',
            'company_name.max' => 'A razão social não pode ter mais que 150 caracteres.',
            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.max' => 'O CNPJ não pode ter mais que 18 caracteres.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado.',
            'state_registration.required' => 'O campo inscrição estadual é obrigatório.',
            'state_registration.max' => 'A inscrição estadual não pode ter mais que 20 caracteres.',
            'address.required' => 'O campo endereço é obrigatório.',
            'address.max' => 'O endereço não pode ter mais que 150 caracteres.',
            'neighborhood.required' => 'O campo bairro é obrigatório.',
            'neighborhood.max' => 'O bairro não pode ter mais que 100 caracteres.',
            'city.required' => 'O campo cidade é obrigatório.',
            'city.max' => 'A cidade não pode ter mais que 100 caracteres.',
            'zip_code.required' => 'O campo CEP é obrigatório.',
            'zip_code.max' => 'O CEP não pode ter mais que 10 caracteres.',
            'state.required' => 'O campo estado é obrigatório.',
            'state.size' => 'O estado deve ter exatamente 2 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'contact_name_1.required' => 'O campo nome do contato 1 é obrigatório.',
            'contact_name_1.max' => 'O nome do contato 1 não pode ter mais que 100 caracteres.',
            'phone_1.required' => 'O campo telefone 1 é obrigatório.',
            'phone_1.max' => 'O telefone 1 não pode ter mais que 20 caracteres.',
            'contact_name_2.max' => 'O nome do contato 2 não pode ter mais que 100 caracteres.',
            'phone_2.max' => 'O telefone 2 não pode ter mais que 20 caracteres.',
            'is_active.boolean' => 'O campo status deve ser verdadeiro ou falso.',
        ];
    }
}
