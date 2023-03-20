<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;


class UserController extends Controller
{

    private UserService $userService;

    /**
     * Inject the dependencies into the controller class.
     *
     * @param UserService $usersService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = QueryBuilder::for($this->userService->index())
        ->defaultSort('-created_at')
        ->paginate($request->per_page);


        return ResponseBuilder::asSuccess()
            ->withMessage('Users retrieved successfully.')
            ->withData(['users' => $users])
            ->build();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return ResponseBuilder::asSuccess()
            ->withMessage('User deleted successfully.')
            ->build();
    }

    /**
     * Restore the specified deleted resource from storage.
     *
     * @param  \App\Models\user  $userws
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(User $user)
    {
        $user->restore();

        return ResponseBuilder::asSuccess()
            ->withMessage('user restored successfully.')
            ->build();
    }
}
