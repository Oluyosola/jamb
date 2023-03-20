<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static BLOG()
 * @method static static FORUM()
 * @method static static DIRECTORY()
 */
final class PostType extends Enum
{
    public const BLOG = 'blog';
    public const FORUM = 'forum';
    public const DIRECTORY = 'directory';
}
