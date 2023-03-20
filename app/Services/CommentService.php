<?php

namespace App\Services;

use App\Http\Requests\API\V1\Generic\Comment\StoreCommentRequest;
use App\Http\Requests\API\V1\Generic\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CommentService
{
    /**
     * Get all comments.
     */
    public function index()
    {
        return Comment::query();
    }

    /**
     * Display specified resource from storage.
     *
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Post $post, Comment $comment): Comment
    {
        return $comment;
    }

    /**
     * Store a new comment on a post.
     *
     * @param StoreCommentRequest $request
     * @param Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function commentOnPost(StoreCommentRequest $request, Post $post)
    {
        $comment = new Comment();
        $comment->body = $request->body;
        $comment->parent_id = $request->parent_id;
        $comment->user()->associate($request->user());
        $comment->commentable()->associate($post);
        $comment->save();

        return $comment;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCommentRequest $request
     * @param \App\Models\Post $post
     * @param \App\Models\Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateCommentOnPost(UpdateCommentRequest $request, Post $post, Comment $comment)
    {
        $comment->body = $request->body;
        $comment->update();

        return $comment;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Comment $comment)
    {
        return $comment->delete() ? true : false;
    }
}
