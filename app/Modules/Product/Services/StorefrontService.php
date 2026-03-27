<?php

namespace App\Modules\Product\Services;

use App\Models\TermAcceptance;
use App\Modules\Product\Repositories\StorefrontRepository;
use Illuminate\Http\Request;

class StorefrontService
{
    public function __construct(private readonly StorefrontRepository $repository) {}

    public function getDataForIndex(array $filters): array
    {
        return [
            'products' => $this->repository->getFilteredProducts($filters),
            'featuredProducts' => $this->repository->getFeaturedProducts(),
            'onSaleProducts' => $this->repository->getOnSaleProducts(),
            'brands' => $this->repository->getAllBrands(),
            'filters' => $filters,
        ];
    }

    public function recordTermAcceptance(Request $request): TermAcceptance
    {
        return TermAcceptance::create([
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $this->sanitizeUserAgent($request->userAgent()),
            'accepted_at' => now(),
            'term_version' => config('app.term_version', '1.0'),
        ]);
    }

    private function sanitizeUserAgent(?string $userAgent): string
    {
        return substr($userAgent ?? 'unknown', 0, 255);
    }
}
