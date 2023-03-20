<?php

namespace App\Http\Controllers\API\V1\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated admin's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        if (!hash_equals((string) $request->id, (string) $request->user('user')->getKey())) {
            throw new AuthorizationException();
        }

        if (!hash_equals((string) $request->hash, sha1($request->user('user')->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($request->user('user')->hasVerifiedEmail()) {
            return ResponseBuilder::asError(400)
                ->withHttpCode(Response::HTTP_BAD_REQUEST)
                ->withMessage('User email has previously being verified')
                ->build();
        }

        if ($request->user('user')->markEmailAsVerified()) {
            event(new Verified($request->user('user')));
        }

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User email verified successfully!')
            ->build();
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user('user')->hasVerifiedEmail()) {
            return ResponseBuilder::asError(400)
                ->withHttpCode(Response::HTTP_BAD_REQUEST)
                ->withMessage('User already has a verified email')
                ->build();
        }

        $request->user('user')->sendEmailVerificationNotification();

        return ResponseBuilder::asSuccess(0)
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('We have sent you another email verification link')
            ->build();
    }
}
