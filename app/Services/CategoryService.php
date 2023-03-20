<?php

namespace App\Services;

use App\Http\Requests\API\V1\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\API\V1\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get all categories.
     */
    public function index()
    {
        $category = Category::class;

        return $category;
    }

    /**
     * Store a new Category.
     *
     * @param StoreCategoryRequest $reques
     * @ return Category $category
     */
    public function store(StoreCategoryRequest $request): Category
    {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->is_active = $request->is_active ? true : false;
        $category->save();

        return $category;
    }

    /**
     * Store a new Category.
     *
     * @param StoreCategoryRequest $reques
     * @ return Category $category
     */
    public function show(Category $category): Category
    {
        return $category;
    }

    /**
     * Update a category.
     *
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return Category $category
     */
    public function update(UpdateCategoryRequest $request, Category $category): Category
    {
        $category->name = $request->name;
        $category->description = $request->description;
        $category->is_active = $request->is_active ? true : false;
        $category->slug = Str::slug($request->name);
        $category->update();

        return $category;
    }

    /**
     * Delete the specified category.
     *
     * @param Category $category
     * @return bool
     */
    public function destroy(Category $category): bool
    {
        return $category->delete() ? true : false;
    }

    /**
     * Restore the specified category.
     *
     * @param Category $category
     * @return bool
     */
    public function restore(Category $category)
    {
        return $category->restore();
    }
}
