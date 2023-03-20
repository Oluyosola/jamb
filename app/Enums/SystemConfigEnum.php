<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CALLOUTCHARGEFEE()
 * @method static static WALLETBONUSAMOUNT()
 */
final class SystemConfigEnum extends Enum
{
    public const CALLOUTCHARGEFEE = 500;
    public const WALLETBONUSAMOUNT = 10000;
}
