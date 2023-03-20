<?php

namespace App\Events\User;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResetPassword
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Authenticatable $user;
    public string $callbackContactUrl;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string $callbackContactUrl
     */
    public function __construct(Authenticatable $user, string $callbackContactUrl)
    {
        $this->user = $user;
        $this->callbackContactUrl = $callbackContactUrl;
    }
}
