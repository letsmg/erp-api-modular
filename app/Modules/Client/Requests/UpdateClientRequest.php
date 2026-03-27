<?php

namespace App\Modules\Client\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isStaff();
    }

    public function rules(): array
    {
        $client = $this->route('client');

        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'document_number' => ['required', 'string', 'max:30', Rule::unique('clients', 'document_number')->ignore($client?->id)],
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
}
