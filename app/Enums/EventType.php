<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static VISITED()
 * @method static static CREATED()
 * @method static static UPDATED()
 * @method static static DELETED()
 */
final class EventType extends Enum
{
    public const VISITED = 'visited';
    public const CREATED = 'created';
    public const UPDATED = 'updated';
    public const DELETED = 'deleted';
}
