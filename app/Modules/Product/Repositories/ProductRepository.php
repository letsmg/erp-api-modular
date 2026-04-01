<?php

namespace App\Modules\Product\Repositories;

use App\Helpers\SanitizerHelper;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Supplier;

class ProductRepository
{
    public function getFiltered(array $filters)
    {
        $query = Product::query()
            ->with(['supplier:id,company_name', 'category:id,name', 'images' => function ($q) {
                $q->orderBy('order', 'asc');
            }]);

        if (isset($filters['blocked']) && $filters['blocked'] == 1) {
            $query->where('is_active', false);
        }

        if (isset($filters['active']) && $filters['active'] == 1) {
            $query->where('is_active', true);
        }

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $searchTerm = "%{$search}%";

                $q->where('description', 'like', $searchTerm)
                    ->orWhere('brand', 'like', $searchTerm)
                    ->orWhere('model', 'like', $searchTerm);

                $numericValue = $this->parseNumeric($search);
                if ($numericValue > 0) {
                    $q->orWhere('sale_price', '<=', $numericValue)
                        ->orWhere('promo_price', '<=', $numericValue);
                }
            });

            $query->orderByRaw('COALESCE(promo_price, sale_price) DESC');
        } else {
            $query->latest();
        }

        return $query->paginate(12)->withQueryString();
    }

    public function getActiveSuppliers()
    {
        return Supplier::select('id', 'company_name')
            ->where('is_active', true)
            ->orderBy('company_name')
            ->get();
    }

    public function getActiveCategories()
    {
        return Category::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function create(array $data)
    {
        $user = auth()->user();
        
        // Sanitiza todos os dados antes de processar
        $data = SanitizerHelper::sanitize($data);
        
        $data['is_active'] = $user?->isAdmin() ?? false;

        $seoFields = ['meta_title', 'meta_description', 'meta_keywords', 'h1', 'text1', 'h2', 'text2', 'schema_markup', 'google_tag_manager'];
        $productData = collect($data)->except($seoFields)->toArray();

        $product = Product::create($productData);

        $seoData = collect($data)->only($seoFields)->filter()->toArray();
        if (! empty($seoData)) {
            // Aplica sanitização específica para dados SEO
            $seoData = SanitizerHelper::sanitize($seoData, ['schema_markup', 'google_tag_manager']);
            $product->seo()->create($seoData);
        }

        return $product;
    }

    public function update(Product $product, array $data)
    {
        // Sanitiza todos os dados antes de processar
        $data = SanitizerHelper::sanitize($data);
        
        $productFields = [
            'description', 'supplier_id', 'category_id', 'barcode', 'brand', 'model',
            'collection', 'size', 'gender', 'stock_quantity', 'slug', 'cost_price',
            'sale_price', 'promo_price', 'promo_start_at', 'promo_end_at', 'weight',
            'width', 'height', 'length', 'free_shipping', 'is_active', 'is_featured',
        ];

        $filteredData = collect($data)->only($productFields)->toArray();

        $user = auth()->user();
        if ($user && ! $user->isAdmin()) {
            unset($filteredData['is_active'], $filteredData['is_featured']);
        }

        $product->update($filteredData);

        return $product;
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => ! $product->is_featured]);

        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }

    private function parseNumeric($value)
    {
        $cleaned = preg_replace('/[^0-9,.]/', '', str_replace(',', '.', $value));
        return is_numeric($cleaned) ? (float) $cleaned : 0;
    }
}
