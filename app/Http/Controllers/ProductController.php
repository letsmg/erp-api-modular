<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Requests\StoreProductRequest;
use App\Modules\Product\Requests\UpdateProductRequest;
use App\Modules\Product\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly ProductService $service,
        private readonly ProductRepository $repository
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        return Inertia::render('Products/Index', [
            'initialFilters' => $request->all(['search', 'blocked']),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        return Inertia::render('Products/Create');
    }

    public function store(StoreProductRequest $request)
    {
        $this->service->storeProduct($request->validated(), $request);
        return redirect()->route('products.index')->with('message', 'Produto cadastrado!');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        return Inertia::render('Products/Edit', [
            'productId' => $product->id,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);
        $this->service->updateProduct($product, $request->validated(), $request);

        return redirect()->route('products.index')->with('message', 'Produto atualizado com sucesso!');
    }

    public function toggle(Product $product)
    {
        $this->authorize('toggle', $product);
        $product->update(['is_active' => ! $product->is_active]);
        return back()->with('message', 'Status de ativacao atualizado!');
    }

    public function toggleFeatured(Product $product)
    {
        $this->authorize('toggle', $product);
        $this->repository->toggleFeatured($product);
        return back()->with('message', 'Status de destaque atualizado!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->service->deleteProduct($product);
        return redirect()->route('products.index')->with('message', 'Removido com sucesso.');
    }

    public function preview(Product $product)
    {
        $product->load(['supplier', 'images']);
        return Inertia::render('Products/Preview', ['product' => $product]);
    }
}
