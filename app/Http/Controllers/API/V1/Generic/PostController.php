<?php

namespace App\Http\Controllers\API\V1\Generic;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    private PostService $postService;

    /**
     * Inject the service to the controller.
     *
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $posts = $this->postService->index();
        $posts = QueryBuilder::for($posts)
                ->defaultSort('-created_at')
                ->allowedIncludes([
                    'likes',
                    'tags',
                    'comments',
                    'categories',
                    'comments.replies',
                    'comments.replies.user',
                    'user',
                    'comments.user',
                ])
                ->allowedFilters([
                    'title',
                    'slug',
                    'post_type',
                    'categories.name',
                ])
                ->published()
                ->approved()
                ->latest()
                ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Posts fetched successfully')
            ->withData(['posts' => $posts])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     * @throws ModelNotFoundException
     */
    public function show(Request $request, Post $post)
    {
        $post = $this->postService->show($post);
        $relatedPosts = $this->postService->getRelatedPosts($post);

        // Register visit to this route
        $post->visit($request->ip());

        if (!($post->is_approved && $post->is_published)) {
            throw new ModelNotFoundException();
        }

        $post = QueryBuilder::for($post)
            ->allowedAppends(['viewCount'])
            ->allowedIncludes([
                'likes',
                'categories',
                'comments',
                'comments.replies',
                'tags',
                'comments.likes',
                'comments.likes.user',
                'comments.replies.user',
                'postable',
                'comments.user',
            ])
            ->findOrFail($post->id);

        return ResponseBuilder::asSuccess()
            ->withData([
                'post' => $post,
                'relatedPosts' => $relatedPosts,
            ])
            ->withMessage('Post fetched successfully')
            ->build();
    }
}
