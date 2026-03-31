<?php

namespace App\Modules\Client\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStaff();
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'document_number' => ['required', 'string', 'max:30', 'unique:clients,document_number'],
            'phone' => ['nullable', 'string', 'max:20'],
            'phone1' => ['nullable', 'string', 'max:20'],
            'contact1' => ['nullable', 'string', 'max:255'],
            'phone2' => ['nullable', 'string', 'max:20'],
            'contact2' => ['nullable', 'string', 'max:255'],
            'address.zip_code' => ['required', 'string', 'max:10'],
            'address.street' => ['required', 'string', 'max:255'],
            'address.number' => ['required', 'string', 'max:10'],
            'address.neighborhood' => ['required', 'string', 'max:255'],
            'address.city' => ['required', 'string', 'max:255'],
            'address.state' => ['required', 'string', 'size:2'],
            'address.complement' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'O usuário selecionado não existe.',
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais que 255 caracteres.',
            'document_number.required' => 'O campo CPF/CNPJ é obrigatório.',
            'document_number.max' => 'O CPF/CNPJ não pode ter mais que 30 caracteres.',
            'document_number.unique' => 'Este CPF/CNPJ já está cadastrado.',
            'phone.max' => 'O telefone não pode ter mais que 20 caracteres.',
            'phone1.max' => 'O telefone 1 não pode ter mais que 20 caracteres.',
            'contact1.max' => 'O contato 1 não pode ter mais que 255 caracteres.',
            'phone2.max' => 'O telefone 2 não pode ter mais que 20 caracteres.',
            'contact2.max' => 'O contato 2 não pode ter mais que 255 caracteres.',
            'address.zip_code.required' => 'O campo CEP é obrigatório.',
            'address.zip_code.max' => 'O CEP não pode ter mais que 10 caracteres.',
            'address.street.required' => 'O campo rua é obrigatório.',
            'address.street.max' => 'A rua não pode ter mais que 255 caracteres.',
            'address.number.required' => 'O campo número é obrigatório.',
            'address.number.max' => 'O número não pode ter mais que 10 caracteres.',
            'address.neighborhood.required' => 'O campo bairro é obrigatório.',
            'address.neighborhood.max' => 'O bairro não pode ter mais que 255 caracteres.',
            'address.city.required' => 'O campo cidade é obrigatório.',
            'address.city.max' => 'A cidade não pode ter mais que 255 caracteres.',
            'address.state.required' => 'O campo estado é obrigatório.',
            'address.state.size' => 'O estado deve ter exatamente 2 caracteres.',
            'address.complement.max' => 'O complemento não pode ter mais que 255 caracteres.',
        ];
    }
}
