<?php

use App\Http\Controllers\API\V1\Generic\CategoryController;
use App\Http\Controllers\API\V1\Generic\CommentController;
use App\Http\Controllers\API\V1\Generic\DirectoryCompanyController;
use App\Http\Controllers\API\V1\Generic\DirectoryCompanyLocationController;
use App\Http\Controllers\API\V1\Generic\DirectoryCompanySocialController;
use App\Http\Controllers\API\V1\Generic\LocationController;
use App\Http\Controllers\API\V1\Generic\Payment\PaystackController;
use App\Http\Controllers\API\V1\Generic\PostController;
use App\Http\Controllers\API\V1\Generic\SearchController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category}', [CategoryController::class, 'show']);
});

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::prefix('/{post}')->group(function () {
        Route::get('/', [PostController::class, 'show']);

        Route::prefix('comments')->group(function () {
            Route::post('/', [CommentController::class, 'commentOnPost'])->middleware('auth:user');
            Route::get('/{comment}', [CommentController::class, 'show']);
            Route::patch('/{comment}', [CommentController::class, 'update'])->middleware('auth:user');
            Route::delete('/{comment}', [CommentController::class, 'destroy'])->middleware('auth:user');
        });
    });
});

Route::prefix('directory-companies')->group(function () {
    Route::get('/', [DirectoryCompanyController::class, 'index']);
    Route::prefix('/{directoryCompany}')->group(function () {
        Route::get('/', [DirectoryCompanyController::class, 'show']);

        Route::prefix('/directory-company-locations')->group(function () {
            Route::get('/', [DirectoryCompanyLocationController::class, 'index']);
            Route::prefix('/{directoryCompanyLocation}')->group(function () {
                Route::get('/', [DirectoryCompanyLocationController::class, 'show']);
            });
        });

        Route::prefix('/directory-company-social')->group(function () {
            Route::get('/', [DirectoryCompanySocialController::class, 'index']);
            Route::prefix('/{directoryCompanySocial}')->group(function () {
                Route::get('/', [DirectoryCompanySocialController::class, 'show']);
            });
        });
    });
});

//Location

Route::get('countries', [LocationController::class, 'getCountries']);
Route::get('states/{country}', [LocationController::class, 'getStates']);
Route::get('cities/{state}', [LocationController::class, 'getCities']);

Route::any('/search', SearchController::class);

Route::post('paystack/webhook', [PaystackController::class, 'webhook']);
