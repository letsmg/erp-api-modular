<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TermAcceptance;

class StoreController extends Controller
{
    /**
     * Vitrine Principal com Filtros e Destaques
     */
    public function index(Request $request)
    {
        // 1. Query base para a listagem principal (com filtros e SEO)
        $query = Product::query()
            ->with(['images', 'seo'])
            ->where('is_active', true);

        // Filtros (Busca com ilike para Postgres, Preço, Marca...)
        if ($request->filled('search') && strlen($request->search) >= 3) {
            $query->where('description', 'ilike', '%' . $request->search . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('sale_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('sale_price', '<=', $request->max_price);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        return Inertia::render('Store/Index', [
            // Listagem paginada (Grid principal)
            'products' => $query->orderBy('created_at', 'desc')
                                ->paginate(9)
                                ->withQueryString(),

            // 2. PRODUTOS EM DESTAQUE (Carousel Superior)
            'featuredProducts' => Product::with(['images', 'seo'])
                                    ->where('is_active', true)
                                    ->inRandomOrder()
                                    ->limit(5)
                                    ->get(),

            // 3. PRODUTOS EM PROMOÇÃO (Mini Carousel)
            'onSaleProducts' => Product::with(['images'])
                                    ->where('is_active', true)
                                    ->orderBy('sale_price', 'asc')
                                    ->limit(8)
                                    ->get(),

            'brands'  => Product::distinct()->whereNotNull('brand')->pluck('brand'),
            'filters' => $request->only(['search', 'min_price', 'max_price', 'brand']),
            
            'ads' => [
                ['id' => 1, 'title' => 'Cupom BEMVINDO10']
            ]
        ]);
    }

    /**
     * Exibição Detalhada do Produto (Substituindo o antigo Modal)
     */
    public function show($id)
    {
        // Carrega o produto com imagens, SEO e categoria (se houver)
        $product = Product::with(['images', 'seo'])
            ->where('is_active', true)
            ->findOrFail($id);

        // Busca produtos relacionados da mesma marca ou categoria (limitado a 4)
        $relatedProducts = Product::with('images')
            ->where('brand', $product->brand)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return Inertia::render('Store/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }

    /**
     * Preview Público (Acessível via link de aprovação ou guest)
     */
    public function preview($id)
    {
        // No preview, ignoramos o 'is_active' para que o admin possa ver antes de publicar
        $product = Product::with(['images', 'seo'])->findOrFail($id);

        return Inertia::render('Products/Preview', [
            'product' => $product,
            'isAdminPreview' => auth()->check() // Informa ao front se é um admin visualizando
        ]);
    }

    /**
     * Aceite de Termos (LGPD / Auditoria de Segurança)
     */
    public function acceptTerms(Request $request)
    {
        // Validação básica para garantir o aceite
        TermAcceptance::create([
            'user_id' => auth()->id(), // null se for guest, ok pela sua lógica
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'accepted_at' => now(),
            'term_version' => '1.0'
        ]);

        return back()->with('success', 'Termos aceitos com sucesso.'); 
    }
}