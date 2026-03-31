<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Repositories\StorefrontRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function __construct(private readonly StorefrontRepository $repository) {}

    public function home(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'min_price', 'max_price', 'brand']);
        $products = $this->repository->getFilteredProducts($filters);

        return $this->success([
            'products' => $products->items(),
            'featured_products' => $this->repository->getFeaturedProducts(),
            'on_sale_products' => $this->repository->getOnSaleProducts(),
            'brands' => $this->repository->getAllBrands(),
            'filters' => $filters,
        ], 'Catalogo da vitrine carregado com sucesso.', 200, [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with(['supplier', 'category', 'images', 'seo'])
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%'.trim((string) $request->string('search')).'%';
                $query->where(function ($inner) use ($term) {
                    $inner->where('description', 'like', $term)
                        ->orWhere('brand', 'like', $term)
                        ->orWhere('model', 'like', $term);
                });
            })
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('supplier_id'), fn ($query) => $query->where('supplier_id', $request->integer('supplier_id')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return $this->paginated($products, 'Catalogo publico carregado com sucesso.');
    }

    public function show(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        $product->load(['supplier', 'category', 'images', 'seo']);

        $relatedProducts = Product::with(['supplier', 'category', 'images'])
            ->where('brand', $product->brand)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return $this->success([
            'product' => $product,
            'related_products' => $relatedProducts,
        ]);
    }
}
