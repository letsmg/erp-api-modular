<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use App\Modules\Product\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly ProductService $service,
        private readonly ProductRepository $repository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Product::class);

        return $this->paginated(
            $this->repository->getFiltered($request->only(['search', 'blocked', 'active'])),
            'Produtos carregados com sucesso.'
        );
    }

    public function formOptions(): JsonResponse
    {
        $this->authorize('create', Product::class);

        return $this->success([
            'suppliers' => $this->repository->getActiveSuppliers(),
            'categories' => $this->repository->getActiveCategories(),
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('create', Product::class);

        $product = $this->service->storeProduct($request->validated(), $request);

        return $this->created($product->load(['supplier', 'category', 'images', 'seo']), 'Produto criado com sucesso.');
    }

    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        return $this->success($product->load(['supplier', 'category', 'images', 'seo']));
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $this->service->updateProduct($product, $request->validated(), $request);

        return $this->success($product->refresh()->load(['supplier', 'category', 'images', 'seo']), 'Produto atualizado com sucesso.');
    }

    public function toggle(Product $product): JsonResponse
    {
        $this->authorize('toggle', $product);

        $product->update(['is_active' => ! $product->is_active]);

        return $this->success($product->refresh(), 'Status do produto atualizado com sucesso.');
    }

    public function toggleFeatured(Product $product): JsonResponse
    {
        $this->authorize('toggle', $product);

        $this->repository->toggleFeatured($product);

        return $this->success($product->refresh(), 'Destaque do produto atualizado com sucesso.');
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $this->service->deleteProduct($product);

        return $this->deleted('Produto removido com sucesso.');
    }
}

