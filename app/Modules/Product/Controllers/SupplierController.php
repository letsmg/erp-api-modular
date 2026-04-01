<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Supplier;
use App\Modules\Product\Requests\StoreSupplierRequest;
use App\Modules\Product\Requests\UpdateSupplierRequest;
use App\Modules\Product\Services\SupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
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

    /**
     * Retorna resposta JSON de recurso excluído (status 204)
     */
    protected function deleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => null
        ], 204);
    }
}
