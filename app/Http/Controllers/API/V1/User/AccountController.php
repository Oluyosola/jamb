<?php

namespace App\Http\Controllers\API\V1\User;

use App\Enums\MediaCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\UpdatePasswordRequest;
use App\Http\Requests\API\V1\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class AccountController extends Controller
{
    /**
     * Get authenticated user's details.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $user = QueryBuilder::for(User::where('id', $request->user()->id))
            ->firstOrFail();

        return ResponseBuilder::asSuccess()
            ->withMessage('User\'s Profile fetched successful!!!')
            ->withData([
                'user' => $user,
            ])
            ->build();
    }

    /**
     * Update profile.
     *
     * @param UpdateUserRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateUserRequest $request)
    {
        DB::beginTransaction();

        $user = $request->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->update();
        if ($request->profile_picture) {
            $user->addMediaFromRequest('profile_picture')->toMediaCollection(MediaCollection::PROFILEPICTURE);
        }

        DB::commit();

        return ResponseBuilder::asSuccess()
            ->withMessage('User profile updated successfully.')
            ->withData([
                'user' => $user,
            ])
            ->build();
    }

    /**
     * Update user password.
     *
     * @param UpdatePasswordRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return ResponseBuilder::asSuccess()
            ->withMessage('User password updated successfully')
            ->withData(['user' => $user])
            ->build();
    }
}
