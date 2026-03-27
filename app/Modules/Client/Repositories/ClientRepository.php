<?php

namespace App\Modules\Client\Repositories;

use App\Modules\Client\Models\Client;

class ClientRepository
{
    public function paginate($search = null)
    {
        return Client::query()
            ->with(['user', 'address'])
            ->when($search, function ($query) use ($search) {
                $term = '%'.trim($search).'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('document_number', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('phone1', 'like', $term);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }
}
