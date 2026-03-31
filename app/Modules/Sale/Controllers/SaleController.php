<?php

namespace App\Modules\Sale\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Sale\Models\Sale;
use App\Modules\Sale\Requests\StoreSaleRequest;
use App\Modules\Sale\Requests\UpdateSaleRequest;
use App\Modules\Sale\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function __construct(private readonly SaleService $service) {}

    public function index(Request $request): JsonResponse
    {
        return $this->paginated($this->service->paginate($request->input('status')), 'Vendas carregadas com sucesso.');
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        return $this->created($this->service->create($request->validated()), 'Venda criada com sucesso.');
    }

    public function show(Sale $sale): JsonResponse
    {
        return $this->success($sale->load(['client.address', 'items']));
    }

    public function update(UpdateSaleRequest $request, Sale $sale): JsonResponse
    {
        return $this->success($this->service->update($sale, $request->validated()), 'Venda atualizada com sucesso.');
    }

    public function destroy(Sale $sale): JsonResponse
    {
        $this->service->delete($sale);

        return $this->deleted('Venda removida com sucesso.');
    }
}
