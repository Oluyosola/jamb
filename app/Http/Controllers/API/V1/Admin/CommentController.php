<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    private $commentService;

    /**
     * Inject models
     *
     * @param CommentService $commentService
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $comments = $this->commentService->index();

        $comments = QueryBuilder::for($comments)
            ->allowedIncludes([
                'user',
                'commentable',
                'replies',
                'replies.user',
                'likes'
            ])
            ->allowedFilters([
                'commentable_id',
            ])
            ->whereNull('parent_id')
            ->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withMessage('Comments fetched successfully')
            ->withData(['comments' => $comments])
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Comment $comment)
    {
        $comment = $comment->load([
            'replies',
            'user',
            'parent',
            // 'likes'
        ]);
        return ResponseBuilder::asSuccess()
            ->withMessage('Comment fetched successfully')
            ->withData(['comments' => $comment])
            ->build();
    }

    /**
     * Toggle approval status of a comment.
     *
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleCommentApproval(Comment $comment)
    {
        $comment->is_approved = !$comment->is_approved;
        $comment->update();

        return ResponseBuilder::asSuccess()
            ->withMessage('Comment approval status updated successfully')
            ->withData(['comment' => $comment])
            ->build();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return ResponseBuilder::asSuccess()
            ->withMessage('Comment deleted successfully!')
            ->build();
    }
}
