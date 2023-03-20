<?php

use App\Http\Controllers\API\V1\Admin\AccountController;
use App\Http\Controllers\API\V1\Admin\ArtisanController;
use App\Http\Controllers\API\V1\Admin\AssociationController;
use App\Http\Controllers\API\V1\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\API\V1\Admin\Auth\LoginController;
use App\Http\Controllers\API\V1\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\API\V1\Admin\Auth\VerificationController;
use App\Http\Controllers\API\V1\Admin\BankDetailController;
use App\Http\Controllers\API\V1\Admin\CategoryController;
use App\Http\Controllers\API\V1\Admin\CommentController;
use App\Http\Controllers\API\V1\Admin\DashboardController;
use App\Http\Controllers\API\V1\Admin\DirectoryCompanyController;
use App\Http\Controllers\API\V1\Admin\DirectoryCompanyLocationController;
use App\Http\Controllers\API\V1\Admin\DirectoryCompanySocialController;
use App\Http\Controllers\API\V1\Admin\PostController;
use App\Http\Controllers\API\V1\Admin\ProductController;
use App\Http\Controllers\API\V1\Admin\ProductGalleryController;
use App\Http\Controllers\API\V1\Admin\TransactionController;
use App\Http\Controllers\API\V1\Admin\SubCategoryController;
use App\Http\Controllers\API\V1\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('/password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('admin.password.sendResetLink');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('admin.password.update');
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::prefix('auth')->group(function () {
        Route::get('/email/verify', [VerificationController::class, 'verify'])->name('admin.verification.verify');
        Route::get('/email/resend-verification', [VerificationController::class, 'resend']);
        Route::post('/logout', [LoginController::class, 'logout']);
    });
    Route::get('/email/resend', [VerificationController::class, 'resend']);

    Route::get('/profile', [AccountController::class, 'index']);
    Route::post('/profile', [AccountController::class, 'update']);
    Route::post('/change-password', [AccountController::class, 'updatePassword']);

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);

        Route::prefix('/{category}')->group(function () {
            Route::get('/', [CategoryController::class, 'show']);
            Route::put('/', [CategoryController::class, 'update']);
            Route::delete('/', [CategoryController::class, 'destroy']);
            Route::post('/restore', [CategoryController::class, 'restore'])->withTrashed();

            Route::prefix('sub-categories')->group(function () {
                Route::get('/', [SubCategoryController::class, 'index']);
                Route::post('/', [SubCategoryController::class, 'store']);

                Route::prefix('/{subCategory}')->group(function () {
                    Route::get('/', [SubCategoryController::class, 'show']);
                    Route::put('/', [SubCategoryController::class, 'update']);
                    Route::delete('/', [SubCategoryController::class, 'destroy']);
                    Route::post('/restore', [SubCategoryController::class, 'restore'])->withTrashed();
                });
            });
        });
    });

    Route::prefix('/posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::prefix('/{post}')->group(function () {
            Route::get('/', [PostController::class, 'show']);
            Route::put('/', [PostController::class, 'update']);
            Route::delete('/', [PostController::class, 'destroy']);
            Route::post('/restore', [PostController::class, 'restore'])->withTrashed();
            Route::patch('/toggle-approval', [PostController::class, 'togglePostApprovalStatus']);
            Route::patch('/toggle-featured', [PostController::class, 'togglePostFeaturedStatus']);
            Route::patch('/toggle-published', [PostController::class, 'togglePostPublishedStatus']);
            Route::patch('/toggle-active', [PostController::class, 'togglePostActiveStatus']);
        });
    });

    Route::prefix('comments')->group(function () {
        Route::get('/', [CommentController::class, 'index']);
        Route::get('/{comment}', [CommentController::class, 'show']);
        Route::patch('/{comment}/toggle-approval', [CommentController::class, 'toggleCommentApproval']);
        Route::delete('/{comment}', [CommentController::class, 'destroy']);
        Route::post('/{comment}/restore', [CommentController::class, 'destroy'])->withTrashed();
    });

    Route::prefix('directory-companies')->group(function () {
        Route::get('/', [DirectoryCompanyController::class, 'index']);
        Route::post('/', [DirectoryCompanyController::class, 'store']);
        Route::prefix('/{directoryCompany}')->group(function () {
            Route::get('/', [DirectoryCompanyController::class, 'show']);
            Route::put('/', [DirectoryCompanyController::class, 'update']);
            Route::delete('/', [DirectoryCompanyController::class, 'destroy']);
            Route::post('/restore', [DirectoryCompanyController::class, 'restore'])->withTrashed();

            Route::prefix('/directory-company-locations')->group(function () {
                Route::get('/', [DirectoryCompanyLocationController::class, 'index']);
                Route::post('/', [DirectoryCompanyLocationController::class, 'store']);
                Route::prefix('/{directoryCompanyLocation}')->group(function () {
                    Route::get('/', [DirectoryCompanyLocationController::class, 'show']);
                    Route::put('/', [DirectoryCompanyLocationController::class, 'update']);
                    Route::delete('/', [DirectoryCompanyLocationController::class, 'destroy']);
                    Route::post('/restore', [DirectoryCompanyLocationController::class, 'restore'])->withTrashed();
                });
            });

            Route::prefix('/directory-company-socials')->group(function () {
                Route::get('/', [DirectoryCompanySocialController::class, 'index']);
                Route::post('/', [DirectoryCompanySocialController::class, 'store']);
                Route::prefix('/{directoryCompanySocial}')->group(function () {
                    Route::get('/', [DirectoryCompanySocialController::class, 'show']);
                    Route::put('/', [DirectoryCompanySocialController::class, 'update']);
                    Route::delete('/', [DirectoryCompanySocialController::class, 'destroy']);
                    Route::post('/restore', [DirectoryCompanySocialController::class, 'restore'])->withTrashed();
                });
            });
        });
    });

    Route::prefix('associations')->group(function () {
        Route::get('/', [AssociationController::class, 'index']);
        Route::post('/', [AssociationController::class, 'store']);
        Route::prefix('/{association}')->group(function () {
            Route::get('/', [AssociationController::class, 'show']);
            Route::put('/', [AssociationController::class, 'update']);
            Route::delete('/', [AssociationController::class, 'destroy']);
            Route::post('/restore', [AssociationController::class, 'restore'])->withTrashed();
        });
    });

    Route::prefix('/bank-details')->group(function () {
        Route::get('/', [BankDetailController::class, 'index']);
        Route::post('/', [BankDetailController::class, 'store']);
        Route::prefix('/{bankDetail}')->group(function () {
            Route::get('/', [BankDetailController::class, 'show']);
            Route::put('/', [BankDetailController::class, 'update']);
            Route::delete('/', [BankDetailController::class, 'destroy']);
            Route::post('/restore', [BankDetailController::class, 'restore'])->withTrashed();
        });
    });

    Route::prefix('/artisans')->group(function () {
        Route::get('/', [ArtisanController::class, 'index']);
        Route::post('/', [ArtisanController::class, 'store']);
        Route::prefix('/{artisan}')->group(function () {
            Route::get('/', [ArtisanController::class, 'show']);
            Route::put('/', [ArtisanController::class, 'update']);
            Route::delete('/', [ArtisanController::class, 'destroy']);
            Route::post('/restore', [ArtisanController::class, 'restore'])->withTrashed();
            Route::post('/toggle-blocked-status', [ArtisanController::class, 'toggleBlockedStatus']);
        });
    });

    // Products
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::prefix('/{product}')->group(function () {
            Route::get('/', [ProductController::class, 'show']);
            Route::put('/', [ProductController::class, 'update']);
            Route::delete('/', [ProductController::class, 'destroy']);
            Route::post('/restore', [ProductController::class, 'restore'])->withTrashed();
            Route::patch('/toggle-active', [ProductController::class, 'toggleActive']);
            Route::patch('/toggle-verified', [ProductController::class, 'toggleVerified']);
            Route::delete('/force-delete', [ProductController::class, 'forceDelete']);

            Route::prefix('/galleries')->group(function () {
                Route::get('/', [ProductGalleryController::class, 'index']);
                Route::post('/', [ProductGalleryController::class, 'store']);

                Route::prefix('/{gallery}')->group(function () {
                    Route::get('/', [ProductGalleryController::class, 'show']);
                    Route::delete('/', [ProductGalleryController::class, 'destroy']);
                    Route::delete('/media/{media}', [ProductGalleryController::class, 'destroyMedia']);
                });
            });
        });
    });
    // Users
    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::prefix('/{user}')->group(function () {
            Route::delete('/', [UserController::class, 'destroy']);
            Route::post('/restore', [UserController::class, 'restore'])->withTrashed();
        });
    });

    // Transaction
    Route::prefix('/transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);   
    });
  
    // Dashboard
    Route::prefix('/dashboard')->group(function () {
        Route::get('/total-stats', [DashboardController::class, 'totalStats']);     
    });

});
