<?php

namespace App\Modules\Product\Services;

use App\Modules\Product\Models\Category;

class CategoryService
{
    public function paginate()
    {
        return Category::with(['parent', 'children'])->orderBy('name')->paginate(15);
    }

    public function create(array $data): Category
    {
        return Category::create($data + ['is_active' => $data['is_active'] ?? true]);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->refresh()->load(['parent', 'children']);
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
