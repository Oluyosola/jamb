<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ADVERT()
 * @method static static WALLETTOPUP()
 * @method static static CALLOUTCHARGE()
 * @method static static CHECKOUT()

 */
final class PaymentPurpose extends Enum
{
    public const ADVERT = 'advert';
    public const WALLETTOPUP = 'wallet-topup';
    public const CALLOUTCHARGE = 'callout-charge';
    public const CHECKOUT = 'checkout';

}
