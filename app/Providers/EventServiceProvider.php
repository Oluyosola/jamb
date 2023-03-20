<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
        ],
        \App\Events\User\ResetPassword::class => [
            \App\Listeners\User\SendResetPasswordConfirmation::class,
        ],
        \App\Events\Generic\SearchLogged::class => [
            \App\Listeners\Generic\LogSearch::class
        ],
        \App\Events\Admin\ResetPassword::class => [
            \App\Listeners\Admin\SendResetPasswordConfirmation::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        \App\Models\Artisan::observe(\App\Observers\ArtisanObserver::class);
        \App\Models\Wallet::observe(\App\Observers\WalletObserver::class);
        \App\Models\Post::observe(\App\Observers\PostObserver::class);
    }
}
