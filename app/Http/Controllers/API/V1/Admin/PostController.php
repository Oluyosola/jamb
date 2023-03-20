<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Post\UpdatePostRequest;
use App\Http\Requests\API\V1\Admin\Post\StorePostRequest;
use App\Interfaces\QueryBuilder\ViewCountInclude;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    /**
     * @var $postService
     */
    public PostService $postService;

    /**
     * Instantiate the class and inject classes it depends on.
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
            ->allowedIncludes([
                'likes',
                'tags',
                'categories',
                'comments',
            ])
            ->allowedFilters([
                'title',
                'slug',
                'categories.name',
                'post_type',
                'is_published',
                'is_featured',
                'is_approved',
            ])
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Posts fetched successfully')
            ->withData(['posts' => $posts])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePostRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StorePostRequest $request)
    {
        $creator = $request->user('admin');
        $post = $this->postService->store($request, $creator);

        return ResponseBuilder::asSuccess()
        ->withMessage('Post created successfully.')
        ->withData(['post' => $post])
        ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Post $post)
    {
        $relatedPosts = $this->postService->getRelatedPosts($post);

        $post = QueryBuilder::for(Post::where('id', $post->id))
            ->allowedIncludes([
                'likes',
                'categories',
                'comments',
                'tags',
                'comments.replies',
                'comments.replies.user',
                'user',
                'comments.user',
                'postable',
                'comments.user',
                AllowedInclude::custom('viewsCount', new ViewCountInclude())
            ])
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('Post fetched successfully')
            ->withData([
                'post' => $post,
                'related_posts' => $relatedPosts
            ])
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post = $this->postService->update($request, $post);

        return ResponseBuilder::asSuccess()
        ->withMessage('Post updated successfully')
        ->withData(['post' => $post])
        ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Post $post)
    {
        $post = $this->postService->destroy($post);

        if ($post) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Post deleted successfully')
            ->build();
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(Post $post)
    {
        $post = $this->postService->restore($post);

        if ($post) {
            return ResponseBuilder::asSuccess()
            ->withMessage('Post restored successfully')
            ->withData(['post' => $post])
            ->build();
        }
    }

    /**
     * Toggle approval status of a post.
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function togglePostApprovalStatus(Post $post)
    {
        $postMessage = $this->postService->togglePostApprovalStatus($post);

        return ResponseBuilder::asSuccess()
            ->withMessage("Post {$postMessage}approved successfully.")
            ->withData(['post' => $post])
            ->build();
    }

    /**
     * Toggle featured status of a post.
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function togglePostFeaturedStatus(Post $post)
    {
        $postMessage = $this->postService->togglePostFeaturedStatus($post);

        return ResponseBuilder::asSuccess()
            ->withMessage("Post {$postMessage}featured successfully.")
            ->withData(['post' => $post])
            ->build();
    }

    /**
     * Toggle active status of a post.
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function togglePostActiveStatus(Post $post)
    {
        $postMessage = $this->postService->togglePostActiveStatus($post);

        return ResponseBuilder::asSuccess()
            ->withMessage("Post {$postMessage}active successfully.")
            ->withData(['post' => $post])
            ->build();
    }

    /**
     * Toggle approval status of a post.
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function togglePostPublishedStatus(Post $post)
    {
        $postMessage = $this->postService->togglePostPublishedStatus($post);

        return ResponseBuilder::asSuccess()
            ->withMessage("Post {$postMessage}published successfully.")
            ->withData(['post' => $post])
            ->build();
    }

    /**
     * Fetch related posts.
     *
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRelatedPosts(Post $post)
    {
        $relatedPosts = $this->postService->getRelatedPosts($post);

        return ResponseBuilder::asSuccess()
            ->withMessage('Related posts fetched successfully')
            ->withData(['related_post' => $relatedPosts])
            ->build();
    }
}
