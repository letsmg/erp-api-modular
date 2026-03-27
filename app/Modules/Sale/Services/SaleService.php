<?php

namespace App\Modules\Sale\Services;

use App\Modules\Sale\Models\Sale;
use App\Modules\Sale\Repositories\SaleRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(private readonly SaleRepository $repository) {}

    public function paginate($status = null)
    {
        return $this->repository->paginate($status);
    }

    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $items = collect($data['items']);

            $sale = $this->repository->create([
                'client_id' => $data['client_id'],
                'sale_date' => $data['sale_date'] ?? now(),
                'status' => $data['status'] ?? 'pending',
                'total_amount' => $this->total($items),
            ]);

            $this->syncItems($sale, $items);

            return $sale->load(['client.address', 'items']);
        });
    }

    public function update(Sale $sale, array $data): Sale
    {
        return DB::transaction(function () use ($sale, $data) {
            $items = collect($data['items']);

            $sale->update([
                'client_id' => $data['client_id'],
                'sale_date' => $data['sale_date'] ?? $sale->sale_date,
                'status' => $data['status'],
                'total_amount' => $this->total($items),
            ]);

            $sale->items()->delete();
            $this->syncItems($sale, $items);

            return $sale->refresh()->load(['client.address', 'items']);
        });
    }

    public function delete(Sale $sale): void
    {
        $sale->delete();
    }

    private function total(Collection $items): float
    {
        return (float) $items->sum(fn (array $item) => $item['quantity'] * $item['unit_price']);
    }

    private function syncItems(Sale $sale, Collection $items): void
    {
        foreach ($items as $item) {
            $sale->items()->create([
                'product_description' => $item['product_description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['quantity'] * $item['unit_price'],
            ]);
        }
    }
}
