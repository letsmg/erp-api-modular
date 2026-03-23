<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TermAcceptance;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query base para a listagem principal (com filtros)
        $query = Product::query()
            ->with(['images'])
            ->where('is_active', true);

        // Filtros (Busca, Preço, Marca...)
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
            // Listagem com Paginação (O que aparece no grid principal)
            'products' => $query->orderBy('created_at', 'desc')
                                ->paginate(9)
                                ->withQueryString(),

            // 2. PRODUTOS EM DESTAQUE (Para o Carousel Superior)
            // Aqui pegamos 5 produtos aleatórios ou os mais caros para o "impacto"
            'featuredProducts' => Product::with(['images'])
                                    ->where('is_active', true)
                                    ->inRandomOrder()
                                    ->limit(5)
                                    ->get(),

            // 3. PRODUTOS EM PROMOÇÃO (Para o Mini Carousel)
            // Simulando promoção pegando os de menor preço ou uma lógica de desconto
            'onSaleProducts' => Product::with(['images'])
                                    ->where('is_active', true)
                                    ->orderBy('sale_price', 'asc')
                                    ->limit(8)
                                    ->get(),

            'brands'  => Product::distinct()->whereNotNull('brand')->pluck('brand'),
            'filters' => $request->only(['search', 'min_price', 'max_price', 'brand']),
            
            // Dados para o banner de Ads (pode ser estático ou vir do banco)
            'ads' => [
                ['id' => 1, 'title' => 'Cupom BEMVINDO10']
            ]
        ]);
    }

    public function acceptTerms(Request $request)
    {
        TermAcceptance::create([
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'accepted_at' => now(),
            'term_version' => '1.0'
        ]);

        return back(); 
    }
}