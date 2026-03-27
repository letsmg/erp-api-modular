<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function products(Request $request)
    {
        $type = $request->query('type', 'sintetico');
        $supplierId = $request->query('supplier_id');

        $products = Product::with(['supplier', 'images'])
            ->when($supplierId, fn ($query) => $query->where('supplier_id', (int) $supplierId))
            ->get()
            ->toArray();

        $data = [
            'products' => $products,
            'type' => $type,
            'title' => 'Relatorio de Produtos - '.strtoupper($type),
            'date' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('reports.products', $data);
        $pdf->setPaper('a4', $type === 'analitico' ? 'landscape' : 'portrait');
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('chroot', public_path());

        return $pdf->stream('relatorio.pdf');
    }
}
