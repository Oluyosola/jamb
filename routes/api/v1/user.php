<?php

use App\Http\Controllers\API\V1\User\AccountController;
use App\Http\Controllers\API\V1\User\Auth\ForgotPasswordController;
use App\Http\Controllers\API\V1\User\Auth\LoginController;
use App\Http\Controllers\API\V1\User\Auth\RegisterController;
use App\Http\Controllers\API\V1\User\Auth\ResetPasswordController;
use App\Http\Controllers\API\V1\User\Auth\VerificationController;
use App\Http\Controllers\API\V1\User\CartController;
use App\Http\Controllers\API\V1\User\DashboardController;
use App\Http\Controllers\API\V1\User\EventController;
use App\Http\Controllers\API\V1\User\LikeController;
use App\Http\Controllers\API\V1\User\OrderController;
use App\Http\Controllers\API\V1\User\PostController;
use App\Http\Controllers\API\V1\User\ProductController;
use App\Http\Controllers\API\V1\User\TransactionController;
use App\Http\Controllers\API\V1\User\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('user.login');
    Route::post('/register', [RegisterController::class, 'register'])->name('user.register');

    Route::post('/password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('user.password.sendResetLink');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('user.password.update');
});

Route::group(['middleware' => ['auth:user', 'log_activity']], function () {
    Route::prefix('auth')->group(function () {
        Route::get('/email/verify', [VerificationController::class, 'verify'])->name('user.verification.verify');
        Route::get('/email/resend-verification', [VerificationController::class, 'resend']);
        Route::post('/logout', [LoginController::class, 'logout'])->name('user.logout');
    });

    Route::get('/profile', [AccountController::class, 'index']);
    Route::post('/profile', [AccountController::class, 'update']);
    Route::post('/change-password', [AccountController::class, 'updatePassword']);

    Route::prefix('/posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::prefix('/{post}')->group(function () {
            Route::get('/', [PostController::class, 'show']);
            Route::put('/', [PostController::class, 'update']);
            Route::delete('/', [PostController::class, 'destroy']);
            Route::post('/restore', [PostController::class, 'restore'])->withTrashed();
            Route::patch('/toggle-active', [PostController::class, 'togglePostActiveStatus']);
        });
    });

    Route::post('/likes', [LikeController::class, 'toggleLike']);

    // Events
    Route::prefix('/events')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'store']);
        Route::prefix('/{event}')->group(function () {
            Route::get('/', [EventController::class, 'show']);
            Route::put('/', [EventController::class, 'update']);
            Route::delete('/', [EventController::class, 'destroy']);
            Route::post('/restore', [EventController::class, 'restore'])->withTrashed();
        });
    });

    // Carts
    Route::prefix('/carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::post('/paystack-checkout', [CartController::class, 'paystackCheckoutIntent']);
        Route::post('/wallet-checkout', [CartController::class, 'checkoutViaWallet']);

        Route::prefix('/{cart}')->group(function () {
            Route::get('/', [CartController::class, 'show']);
            Route::put('/', [CartController::class, 'update']);
            Route::delete('/', [CartController::class, 'destroy']);
            // Route::post('/paystack-checkout', [CartController::class, 'paystackCheckoutIntent']);
            Route::post('/wallet-checkout', [CartController::class, 'checkoutViaWallet']);
        });
    });

    // Order
    Route::prefix('/orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);   
        Route::post('/', [OrderController::class, 'store']);
        Route::post('pay-callout-fee', [OrderController::class, 'payCalloutChargeViaWallet']);
        Route::prefix('/{order}')->group(function () {
            Route::get('/', [OrderController::class, 'show']);
        });
    });

    // Products
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::prefix('/{product}')->group(function () {
            Route::get('/', [ProductController::class, 'show']);
        });
    });

    // Transaction
    Route::prefix('/transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);   
    });

    // Wallet
    Route::get('/wallets', [WalletController::class, 'index']);
    Route::post('/wallets', [WalletController::class, 'store']);
    Route::post('/wallets/topup/paystack', [WalletController::class, 'paystackTopupIntent']);

    // Dashboard
    Route::prefix('/dashboard')->group(function () {
        Route::get('/wallet-balance', [DashboardController::class, 'walletBalance']);     
    });
});

    
