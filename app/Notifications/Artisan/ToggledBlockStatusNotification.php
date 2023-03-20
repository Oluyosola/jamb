<?php

namespace App\Notifications\Artisan;

use App\Models\Artisan;
use App\Models\BlockedAccountMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ToggledBlockStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public BlockedAccountMessage $blockedAccountMessage;
    public string $reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(BlockedAccountMessage $blockedAccountMessage)
    {
        $this->blockedAccountMessage = $blockedAccountMessage;
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
        $artisan = $this->blockedAccountMessage->model;
        $reason = $this->blockedAccountMessage->reason;
        $blockedMessage = "Please note that your account has been blocked. Reasons can be found below.";
        $unblockedMEssage = "Your account has now been unblocked, you can now continue to enjoy services on our platform.";
        $isBlocked = $artisan->is_blocked;
        $message = $isBlocked ? $blockedMessage : $unblockedMEssage;
        return (new MailMessage())
                    ->subject('Account Status Update')
                    ->greeting("Dear {$artisan->full_name}")
                    ->line($message)
                    ->line($isBlocked ? $reason : '')
                    ->salutation('Cheers!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
