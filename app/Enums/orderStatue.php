<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static CREATED()
 * @method static static ACCEPTED()
 * @method static static REJECTED()
 */
final class orderStatue extends Enum
{
    const CREATED = 0;
    const ACCEPTED = 1;
    const REJECTED = 2;
    const PROCESSING = 3;
    const DELIVERING = 4;
    const DONE = 5;
}
