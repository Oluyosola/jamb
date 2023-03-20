<?php

namespace App\Notifications\User;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Wallet $wallet;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
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
        $wallet = $this->wallet;
        $appName = config('app.name');
        return (new MailMessage())
                    ->subject('Wallet Created.')
                    ->greeting("Dear {$wallet?->owner?->full_name},")
                    ->line("A wallet has been created for you on {$appName}. Your wallet id is {$wallet->unique_id}")
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
