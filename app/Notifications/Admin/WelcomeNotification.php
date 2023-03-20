<?php

namespace App\Notifications\Admin;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var string
     * @var string
     */
    public string $callbackUrl;
    public string $token;

    /**
     * Create a new notification instance.
     *
     * @param string $callbackUrl
     * @param string $token
     * @return void
     */
    public function __construct(string $callbackUrl, string $token)
    {
        $this->callbackUrl = $callbackUrl;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $expires = config('auth.passwords.admins.expire');
        $appName = config('app.name');

        return (new MailMessage())
        ->subject(Lang::get("Welcome to {$appName} Admin Portal"))
        ->greeting("Dear $notifiable->full_name")
        ->line(
            Lang::get('An admin account has been created on your behalf on our platform.')
        )
        ->line(
            Lang::get('Kindly reset your password to start using our platform.')
        )
        ->action(Lang::get('Reset Password'), $this->getResetUrl())
        ->line(Lang::get('This password reset link will expire in :count minutes.', [
            'count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')
        ]))
        ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @return string
     */
    protected function getResetUrl()
    {
        return "{$this->callbackUrl}?token={$this->token}";
    }
}
