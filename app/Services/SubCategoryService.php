<?php

namespace App\Services;

use App\Http\Requests\API\V1\Admin\SubCategory\StoreSubCategoryRequest;
use App\Http\Requests\API\V1\Admin\SubCategory\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;

class SubCategoryService
{
    /**
     * Get all sub categories.
     */
    public function index(Category $category)
    {
        $subCategory = $category->subCategories();

        return $subCategory;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSubCategoryRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreSubCategoryRequest $request, Category $category)
    {
        $subCategory = new SubCategory();
        $subCategory->category()->associate($category);
        $subCategory->name = $request->name;
        $subCategory->description = $request->description;
        $subCategory->publish = (bool) $request->publish;
        $subCategory->save();

        return $subCategory;
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
     * @param SubCategory $subCategory
     * @return SubCategory $subCategory
     */
    public function update(UpdateSubCategoryRequest $request, Category $category, SubCategory $subCategory): SubCategory
    {
        $subCategory->category()->associate($category);
        $subCategory->name = $request->name;
        $subCategory->description = $request->description;
        $subCategory->publish = (bool) $request->publish;
        $subCategory->save();

        return $subCategory;
    }

    /**
     * Delete the specified sub category.
     *
     * @param Category $category
     * @param SubCategory $subCategory
     * @return bool
     */
    public function destroy(Category $category, SubCategory $subCategory): bool
    {
        return $subCategory->delete() ? true : false;
    }

    /**
     * Restore the specified sub category.
     *
     * @param Category $category
     * @param SubCategory $subCategory
     * @return bool
     */
    public function restore(Category $category, SubCategory $subCategory)
    {
        return $subCategory->restore();
    }
}