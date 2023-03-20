<?php

namespace App\Http\Controllers\API\V1\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Auth\LoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class LoginController extends Controller
{
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Login existing admin users to the application.
     *
     * @param RegisterRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(LoginRequest $request)
    {
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return ResponseBuilder::asError(Response::HTTP_UNAUTHORIZED)
            ->withMessage('Invalid login details')
            ->build();
        }

        $token = $admin->createToken('cn_admin')->plainTextToken;
        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('Admin login was successful.')
            ->withData([
                'admin' => $admin,
                'token' => $token
            ])
            ->build();
    }

    /**
     * Log user out from current device.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $request)
    {
        $request->user('admin')->currentAccessToken()->delete();

        return ResponseBuilder::asSuccess()
            ->withMessage('Logout was successful.')
            ->build();
    }
}
