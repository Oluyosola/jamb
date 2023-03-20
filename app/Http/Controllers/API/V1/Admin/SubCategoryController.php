<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\SubCategory\StoreSubCategoryRequest;
use App\Http\Requests\API\V1\Admin\SubCategory\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Services\SubCategoryService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class SubCategoryController extends Controller
{
    /**
     * @var $categoryService
     */
    public SubCategoryService $subCategoryService;

    /**
     * Instantiate the class and inject classes it depends on.
     */
    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Category $category
     * @return @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Category $category)
    {
        $subCategories = $this->subCategoryService->index($category);
        $subCategories = QueryBuilder::for($subCategories)
            ->allowedFilters([
                'published',
            ])
            ->allowedIncludes([
                'category',
            ])
            ->paginate();

        return ResponseBuilder::asSuccess()
            ->withMessage("Sub Categories for Category ({$category->name}) fetched successfully.")
            ->withData([
                'sub_categories' => $subCategories,
            ])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSubCategoryRequest $request
     * @return @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreSubCategoryRequest $request, Category $category)
    {
        $subCategory = $this->subCategoryService->store($request, $category);

        return ResponseBuilder::asSuccess()
        ->withMessage("Sub-category stored for Category ({$category->name}) successfully.")
        ->withData([
            'sub_category' => $subCategory,
        ])
        ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Category $category, SubCategory $subCategory)
    {
        $subCategory = QueryBuilder::for(SubCategory::where('id', $subCategory->id))
        ->allowedIncludes([
            'category',
        ])
        ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('Sub-category fetched successfully.')
            ->withData([
                'sub_category' => $subCategory,
            ])
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateSubCategoryRequest  $request
     * @param  \App\Models\SubCategory  $subCategory
     * @return @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateSubCategoryRequest $request, Category $category, SubCategory $subCategory)
    {
        $subCategory = $this->subCategoryService->update($request, $category, $subCategory);

        return ResponseBuilder::asSuccess()
            ->withMessage('Sub-category updated successfully.')
            ->withData([
                'sub_category' => $subCategory,
            ])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Category $category, SubCategory $subCategory)
    {
        $subCategory = $this->subCategoryService->destroy($category, $subCategory);

        if ($subCategory) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Sub Category deleted successfully')
            ->build();
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @param \App\Models\SubCategory $subCategory
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(Category $category, SubCategory $subCategory)
    {
        $subCategory = $this->subCategoryService->restore($category, $subCategory);

        if ($subCategory) {
            return ResponseBuilder::asSuccess()
            ->withMessage('SubCategory restored successfully')
            ->withData(['sub_category' => $subCategory])
            ->build();
        }
    }
}
