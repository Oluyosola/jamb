<?php

namespace App\Http\Controllers\API\V1\Generic;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Generic\Comment\StoreCommentRequest;
use App\Http\Requests\API\V1\Generic\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    private CommentService $commentService;

    /**
     * Inject models.
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
    /**
     * Display specified resource from storage.
     *
     * @param Post $post
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Post $post, Comment $comment)
    {
        $comments = $this->commentService->show($post, $comment);

        $comments = QueryBuilder::for($comments->where('id', $post->id)->approved())
                    ->allowedIncludes([
                        'user',
                        'replies',
                        'replies.user',
                        'likes',
                        'comments.'
                    ])
                    ->paginate();

        return ResponseBuilder::asSuccess()
            ->withMessage("Comment fetched for post({$post->title}) fetched successfully")
            ->withData(['comments' => $comments])
            ->build();
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
        $comment = $this->commentService->commentOnPost($request, $post);

        return ResponseBuilder::asSuccess()
            ->withMessage("Comment saved on post({$post->title}) successfully")
            ->withData(['comment' => $comment])
            ->build();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCommentRequest $request
     * @param \App\Models\Post $post
     * @param \App\Models\Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateCommentRequest $request, Post $post, Comment $comment)
    {
        $this->authorize('update', [$comment, $post]);
        $comment = $this->commentService->updateCommentOnPost($request, $post, $comment);

        return ResponseBuilder::asSuccess()
        ->withMessage("Comment updated ({$post->title}) post successfully")
            ->withData(['comment' => $comment])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Comment $comment
     * @param \App\Models\Post $post
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', [$comment, $post]);
        $this->commentService->destroy($comment);

        return ResponseBuilder::asSuccess()
            ->withMessage('Comment deleted successfully!')
            ->build();
    }
}
