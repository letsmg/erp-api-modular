<?php

namespace App\Modules\Product\Services;

use App\Helpers\SanitizerHelper;
use App\Modules\Product\Models\Supplier;

class SupplierService
{
    public function paginate($search = null)
    {
        return Supplier::query()
            ->when($search, function ($query) use ($search) {
                $term = '%'.trim($search).'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('company_name', 'like', $term)
                        ->orWhere('cnpj', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function create(array $data): Supplier
    {
        $data = SanitizerHelper::sanitize($data);
        return Supplier::create($data + ['is_active' => $data['is_active'] ?? true]);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $data = SanitizerHelper::sanitize($data);
        $supplier->update($data);

        return $supplier->refresh();
    }

    public function delete(Supplier $supplier): void
    {
        $supplier->delete();
    }
}
