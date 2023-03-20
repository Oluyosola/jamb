<?php

namespace App\Services;

use App\Enums\MediaCollection;
use App\Http\Requests\Admin\Post\UpdatePostRequest;
use App\Http\Requests\API\V1\Post\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostService
{

    /**
     * Get all posts in storage.
     */
    public function index()
    {
        return Post::query();
    }

    /**
     * Store a new Post in storage.
     *
     * @param StorePostRequest $request
     * @return Post $post
     */
    public function store(StorePostRequest $request, Authenticatable $creator)
    {
        DB::beginTransaction();

        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->post_type = $request->post_type;
        $post->body = $request->body;
        $post->meta = $request->meta;
        $post->is_featured = $request->is_featured ? true : false;
        $post->is_published = $request->is_published ? true : false;

        $post->is_approved = $request->is_approved ? true : false;

        $post->save();

        $post->categories()->sync($request->categories);

        if ($request->featured_image) {
            $post->addMediaFromRequest('featured_image')->toMediaCollection(MediaCollection::FEATUREDIMAGE);
        }

        // save post tags
        if ($requestTags = $request->tags) {
            $tags = collect();
            foreach ($requestTags as $requestTag) {
                $tag = Tag::where('name', $requestTag)->first();
                if (!$tag) {
                    $tag = new Tag();
                    $tag->name = $requestTag;
                    $tag->save();
                }
                $tags->push($tag);
            }
            $post->tags()->sync($tags->pluck('id'));
        }

        DB::commit();

        return $post;
    }

    /**
     * Show a Post.
     *
     * @return Post $post
     */
    public function show(Post $post)
    {
        return $post;
    }

    /**
     * Update a post.
     *
     * @param UpdatePostRequest $request
     * @param Post $post
     */
    public function update(FormRequest $request, Post $post)
    {
        DB::beginTransaction();

        $post->admin()->associate($request->user('admin'));
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->post_type = $request->post_type;
        $post->body = $request->body;
        $post->meta = $request->meta;
        $post->is_featured = $request->is_featured ? true : false;
        $post->is_published = $request->is_published ? true : false;

        $post->is_approved = $request->is_approved ? true : false;

        $post->update();

        $post->categories()->sync($request->categories);

        if ($request->featured_image) {
            $post->addMediaFromRequest('featured_image')->toMediaCollection(MediaCollection::FEATUREDIMAGE);
        }

        // save post tags
        if ($requestTags = $request->tags) {
            $tags = collect();
            foreach ($requestTags as $requestTag) {
                $tag = Tag::where('name', $requestTag)->first();
                if (!$tag) {
                    $tag = new Tag();
                    $tag->name = $requestTag;
                    $tag->save();
                }
                $tags->push($tag);
            }
            $post->tags()->sync($tags->pluck('id'));
        }

        DB::commit();

        return $post;
    }

     /**
     * Delete the specified post.
     *
     * @param Post $post
     * @return bool
     */
    public function destroy(Post $post): bool
    {
        return $post->delete() ? true : false;
    }

    /**
     * Restore the specified post.
     *
     * @param Post $post
     * @return bool
     */
    public function restore(Post $post)
    {
        return $post->restore();
    }

    /**
     * Update approved status of a post.
     *
     * @param Post $post
     * @return string
     */
    public function togglePostApprovalStatus(Post $post)
    {
        $post->is_approved = !$post->is_approved;
        $post->save();

        $message = $post->is_approved ? '' : 'un';
        return $message;
    }

    /**
     * Update published status of a post.
     *
     * @param Post $post
     * @return string
     */
    public function togglePostPublishedStatus(Post $post)
    {
        $post->is_published = !$post->is_published;
        $post->save();

        $message = $post->is_published ? '' : 'un';
        return $message;
    }

    /**
     * Update active status of a post.
     *
     * @param Post $post
     * @return string
     */
    public function togglePostActiveStatus(Post $post)
    {
        $post->is_active = !$post->is_active;
        $post->save();

        $message = $post->is_active ? '' : 'in';
        return $message;
    }

    /**
     * Update featured status of a post.
     *
     * @param Post $post
     * @return string
     */
    public function togglePostFeaturedStatus(Post $post)
    {
        $post->is_featured = !$post->is_featured;
        $post->save();

        $message = $post->is_featured ? '' : 'un';
        return $message;
    }


    /**
     * Get related post by category and post id
     * @param mixed $categoryId
     * @param mixed $postId
     *
     * @return \Illuminate\Http\Response
     */
    public function getRelatedPosts(Post $post)
    {
        $relatedPosts = Post::whereHas('tags', fn ($q) => $q->whereIn('name', $post->tags->pluck('name'))->limit(3))
            ->orWhereHas('categories', fn ($q) => $q->whereIn('id', $post->categories->pluck('id')))
            ->where('id', '!=', $post->id)
            ->take(3)
            ->inRandomOrder()
            ->get();

        return $relatedPosts;
    }
}
