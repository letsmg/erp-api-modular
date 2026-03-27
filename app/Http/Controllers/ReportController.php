<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Reports/Index', [
            'suppliers' => Supplier::select('id', 'company_name')->orderBy('company_name')->get(),
        ]);
    }

    public function products(Request $request)
    {
        return redirect()->to(route('api.reports.products', $request->query()));
    }
}
