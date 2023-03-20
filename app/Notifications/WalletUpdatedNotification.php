<?php

namespace App\Notifications;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param Wallet $wallet
     * @param array $array
     * @return void
     */
    public function __construct(public Wallet $wallet, public array $array)
    {
        //
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
        $array = $this->array;
        $appName = config('app.name');
        $message = '';

        if ($array['action'] == 'credited') {
            $message = "Your wallet has been {$array['action']} with NGN{$array['amount']}";
        } else {
            $message = "{$array['amount']} has been {$array['action']} from your wallet";
        }

        return (new MailMessage())
            ->subject("Your ${appName} Wallet has been {$array['action']}")
            ->greeting("Dear {$notifiable->full_name}")
            ->line($message)
            ->line('Thanks!');
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
