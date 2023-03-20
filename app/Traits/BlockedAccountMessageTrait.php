<?php

namespace App\Traits;

use App\Models\BlockedAccountMessage;
use App\Models\Wallet;

trait BlockedAccountMessageTrait
{
    /**
     * Create a blocked account message.
     *
     * @return BlockedAccountMessage $blockedMessage
     */
    public function createBlockedAccountMessage($request)
    {
        $modelType = get_class($this);
        $modelId = $this->id;

        $blockedMessage = new BlockedAccountMessage();
        $blockedMessage->model_type = $modelType;
        $blockedMessage->model_id = $modelId;
        $blockedMessage->reason = $request->reason;
        $blockedMessage->save();

        return $blockedMessage;
    }
}
