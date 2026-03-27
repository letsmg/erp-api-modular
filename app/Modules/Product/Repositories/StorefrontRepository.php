<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\Product;

class StorefrontRepository
{
    public function getFilteredProducts(array $filters)
    {
        $query = Product::query()
            ->with(['images', 'seo'])
            ->where('is_active', true);

        if (! empty($filters['search']) && mb_strlen($filters['search']) >= 3) {
            $searchTerm = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'like', $searchTerm)
                  ->orWhere('brand', 'like', $searchTerm);
            });
        }

        if (! empty($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where('sale_price', '>=', (float) $filters['min_price']);
        }

        if (! empty($filters['max_price']) && is_numeric($filters['max_price'])) {
            $query->where('sale_price', '<=', (float) $filters['max_price']);
        }

        if (! empty($filters['brand'])) {
            $query->where('brand', $filters['brand']);
        }

        return $query->orderByDesc('created_at')->paginate(9)->withQueryString();
    }

    public function getFeaturedProducts(int $limit = 5)
    {
        return Product::with(['images', 'seo'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getOnSaleProducts(int $limit = 8)
    {
        return Product::with(['images'])
            ->where('is_active', true)
            ->orderBy('sale_price', 'asc')
            ->limit($limit)
            ->get();
    }

    public function getAllBrands()
    {
        return Product::query()
            ->whereNotNull('brand')
            ->where('is_active', true)
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');
    }
}
