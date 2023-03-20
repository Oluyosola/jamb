<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static FEATUREDIMAGE()
 * @method static LOGO()
 * @method static PROFILEPICTURE()
 * @method static ADVERTBANNER()
 */
final class MediaCollection extends Enum
{
    public const FEATUREDIMAGE = 'featured_image';
    public const LOGO = 'logo';
    public const PROFILEPICTURE = 'profile_picture';
    public const ADVERTBANNER = 'advert_banner';
    public const GALLERY = 'gallery';
}
