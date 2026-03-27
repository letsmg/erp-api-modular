<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Product;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();
        $sitemap->add(Url::create('/')->setPriority(1.0));

        $products = Product::where('is_active', true)->get();
        foreach ($products as $product) {
            $sitemap->add(
                Url::create("/store/product/{$product->slug}")
                    ->setLastModificationDate($product->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        }

        return $sitemap->toResponse(request());
    }
}
