<?php

namespace App\Http\Controllers;

use App\Modules\Product\Models\Supplier;
use App\Modules\Product\Requests\StoreSupplierRequest;
use App\Modules\Product\Requests\UpdateSupplierRequest;
use App\Modules\Product\Services\SupplierService;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function __construct(private readonly SupplierService $service) {}

    public function index()
    {
        return Inertia::render('Suppliers/Index');
    }

    public function create()
    {
        return Inertia::render('Suppliers/Create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('suppliers.index')->with('message', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit(Supplier $supplier)
    {
        return Inertia::render('Suppliers/Edit', ['supplierId' => $supplier->id]);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $this->service->update($supplier, $request->validated());
        return redirect()->route('suppliers.index')->with('message', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Supplier $supplier)
    {
        $this->service->delete($supplier);
        return redirect()->route('suppliers.index')->with('message', 'Fornecedor removido com sucesso!');
    }
}
