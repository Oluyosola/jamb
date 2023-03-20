<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\API\V1\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * @var $categoryService
     */
    public CategoryService $categoryService;

    /**
     * Instantiate the class and inject classes it depends on.
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $category = $this->categoryService->index();

        $categories = QueryBuilder::for($category)
            ->allowedFields([
                'id',
                'name',
                'description',
            ])
            ->allowedIncludes([
                'posts',
                'subCategories',
            ]);

        if ($request->do_not_paginate) {
            $categories = $categories->get();
        } else {
            $categories = $categories->paginate($request->per_page);
        }

        return ResponseBuilder::asSuccess()
            ->withMessage('Categories fetched successfully')
            ->withData(['categories' => $categories])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCategoryRequest  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->store($request);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->is_active = $request->is_active ? true : false;
        $category->save();

        return ResponseBuilder::asSuccess()
            ->withMessage('Category created successfully')
            ->withData(['category' => $category])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Category $category)
    {
        $category = QueryBuilder::for(
            Category::where('id', $category->id)
        )
            ->allowedIncludes([
                'posts',
                'posts.comments',
                'subCategories',
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('Category fetched successfully')
            ->withData(['category' => $category])
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category = $this->categoryService->update($request, $category);

        return ResponseBuilder::asSuccess()
            ->withMessage('Category updated successfully')
            ->withData(['category' => $category])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Category $category)
    {
        $category = $this->categoryService->destroy($category);

        if ($category) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Category deleted successfully')
            ->build();
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(Category $category)
    {
        $category = $this->categoryService->restore($category);

        if ($category) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Category restored successfully')
            ->withData(['data' => $category])
            ->build();
        }
    }
}
