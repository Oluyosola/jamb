<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OPEN()
 * @method static static PENDING()
 * @method static static INPROGRESS()
 * @method static static ACCEPTED()
 * @method static static COMPLETED()
 */
final class OrderStatusEnum extends Enum
{
    public const OPEN = 'open';
    public const PENDING = 'pending';
    public const INPROGRESS = 'in_progress';
    public const ACCEPTED = 'accepted';
    public const COMPLETED = 'completed';
}
