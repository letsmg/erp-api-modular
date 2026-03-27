<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Services\StorefrontService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function __construct(private readonly StorefrontService $service) {}

    public function index(Request $request)
    {
        return Inertia::render('Store/Index', [
            'initialFilters' => $request->only(['search', 'min_price', 'max_price', 'brand']),
        ]);
    }

    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        return Inertia::render('Store/Show', [
            'productSlug' => $product->slug,
        ]);
    }

    public function acceptTerms(Request $request)
    {
        $this->service->recordTermAcceptance($request);
        return back()->with('success', 'Termos aceitos.');
    }
}
