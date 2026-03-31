<?php

namespace App\Modules\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Product\Models\Category;
use App\Modules\Product\Requests\StoreCategoryRequest;
use App\Modules\Product\Requests\UpdateCategoryRequest;
use App\Modules\Product\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $service) {}

    public function index(): JsonResponse
    {
        return $this->paginated($this->service->paginate(), 'Categorias carregadas com sucesso.');
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        return $this->created($this->service->create($request->validated())->load(['parent', 'children']), 'Categoria criada com sucesso.');
    }

    public function show(Category $category): JsonResponse
    {
        return $this->success($category->load(['parent', 'children']));
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        return $this->success($this->service->update($category, $request->validated()), 'Categoria atualizada com sucesso.');
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->service->delete($category);

        return $this->deleted('Categoria removida com sucesso.');
    }
}
