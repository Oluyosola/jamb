<?php

namespace App\Listeners\User;

use App\Events\User\ResetPassword;
use App\Notifications\User\PasswordResetConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendResetPasswordConfirmation implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ResetPassword $event)
    {
        $event->user->notify(new PasswordResetConfirmed($event->callbackContactUrl));
    }
}
