<?php

namespace App\Modules\Sale\Repositories;

use App\Modules\Sale\Models\Sale;

class SaleRepository
{
    public function paginate($status = null)
    {
        return Sale::query()
            ->with(['client.address', 'items'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Sale
    {
        return Sale::create($data);
    }
}
