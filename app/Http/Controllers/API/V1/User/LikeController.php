<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\Like\StoreLikeRequest;
use App\Models\Like;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        return ResponseBuilder::asSuccess()
            ->withMessage('User likes fetched successfully.')
            ->withData([
                'likes' => $request->user()->likes,
            ])
            ->build();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreLikeRequest  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleLike(StoreLikeRequest $request)
    {
        $user = request()->user();
        $model = $request->model;

        switch ($model) {
            case 'post':
                $modelType = \App\Models\Post::class;
                break;
            case 'comment':
                $modelType = \App\Models\Comment::class;
                break;
            default:
                $modelType = '';
                break;
        }

        $modelId = $request->model_id;

        $like = Like::where('user_id', $user->id)
            ->whereHasMorph(
                'likeable',
                [$modelType],
                fn (Builder $query) => $query->where('id', $modelId)
            )
            ->first();

        $action = '';
        if ($like) {
            $action = 'un';
            $like->delete();
        } else {
            $like = new Like();
            $like->likeable_id = $modelId;
            $like->likeable_type = $modelType;
            $like->user_id = $user->id;
            $like->save();
        }

        return ResponseBuilder::asSuccess()
            ->withMessage("{$model} was {$action}liked successfully")
            ->build();
    }
}
