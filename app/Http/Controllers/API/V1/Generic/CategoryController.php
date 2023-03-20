<?php

namespace App\Http\Controllers\API\V1\Generic;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    /**
     * Inject models.
     *
     * @param Category $category
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $categories = $this->categoryService->index();

        $categories = QueryBuilder::for($categories)
            ->orderBy('name')
            ->active()
                ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Categories fetched successfully')
            ->withData(['categories' => $categories])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param mixed $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Category $category)
    {
        $category = QueryBuilder::for(
            $this->categoryService->show($category)->active()
        )
            // ->withCount('posts')
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
}
