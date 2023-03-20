<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\MediaCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\UpdatePasswordRequest;
use App\Http\Requests\API\V1\Admin\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;

class AccountController extends Controller
{
    /**
     * Get authenticated admin's details.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $admin = QueryBuilder::for($request->user('admin'))
            ->allowedIncludes([
                'adminPosts',
            ])
            ->get();

        return ResponseBuilder::asSuccess()
            ->withMessage('Admin\'s Profile fetched successful!!!')
            ->withData([
                'admin' => $admin,
            ])
            ->build();
    }

    /**
     * Update profile.
     *
     * @param UpdateProfileRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateProfileRequest $request)
    {
        DB::beginTransaction();

        $admin = $request->user('admin');
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->phone_number = $request->phone_number;
        $admin->update();

        if ($request->profile_picture) {
            $admin->addMediaFromRequest('profile_picture')->toMediaCollection(MediaCollection::PROFILEPICTURE);
        }

        DB::commit();

        return ResponseBuilder::asSuccess()
            ->withMessage('Admin profile updated successfully.')
            ->withData([
                'admin' => $admin,
            ])
            ->build();
    }

    /**
     * Update admin password.
     *
     * @param UpdatePasswordRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $admin = $request->user('admin');
        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return ResponseBuilder::asSuccess()
            ->withMessage('Admin password updated successfully')
            ->withData(['admin' => $admin])
            ->build();
    }
}
