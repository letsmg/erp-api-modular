<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\ApiController;
use App\Modules\Product\Models\Supplier;
use App\Modules\Product\Requests\StoreSupplierRequest;
use App\Modules\Product\Requests\UpdateSupplierRequest;
use App\Modules\Product\Services\SupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends ApiController
{
    public function __construct(private readonly SupplierService $service) {}

    public function index(Request $request): JsonResponse
    {
        return $this->paginated($this->service->paginate($request->input('search')), 'Fornecedores carregados com sucesso.');
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        return $this->created($this->service->create($request->validated()), 'Fornecedor criado com sucesso.');
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return $this->success($supplier);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        return $this->success($this->service->update($supplier, $request->validated()), 'Fornecedor atualizado com sucesso.');
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $this->service->delete($supplier);

        return $this->deleted('Fornecedor removido com sucesso.');
    }
}
