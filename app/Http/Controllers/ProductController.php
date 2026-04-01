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

        $filters = $request->all(['search', 'blocked']);
        
        $products = $this->repository->getFiltered($filters);

        return Inertia::render('Products/Index', [
            'initialFilters' => $filters,
            'products' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        // Carregar fornecedores e categorias para o formulário
        $suppliers = \App\Modules\Product\Models\Supplier::where('is_active', true)->get();
        $categories = \App\Modules\Product\Models\Category::where('is_active', true)->get();

        return Inertia::render('Products/Create', [
            'initialSuppliers' => $suppliers,
            'initialCategories' => $categories,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $this->service->storeProduct($request->validated(), $request);
        return redirect()->route('products.index')->with('message', 'Produto cadastrado!');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $product->load(['supplier', 'category', 'images', 'seo']);

        return Inertia::render('Products/Edit', [
            'productId' => $product->id,
            'product' => $product,
            'suppliers' => $this->repository->getActiveSuppliers(),
            'categories' => $this->repository->getActiveCategories(),
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
        $updatedProduct = $this->repository->toggleFeatured($product);
        
        return request()->inertia()
            ? back()->with([
                'message' => 'Status de destaque atualizado!',
                'product' => $updatedProduct,
            ])
            : response()->json([
                'message' => 'Status de destaque atualizado!',
                'data' => $updatedProduct,
            ]);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->service->deleteProduct($product);
        return redirect()->route('products.index')->with('message', 'Removido com sucesso.');
    }

    public function preview(Product $product)
    {
        $this->authorize('view', $product);
        $product->load(['supplier', 'images']);
        
        return Inertia::render('Products/Preview', ['product' => $product]);
    }
}
