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
    const CREATED = 1;
    const ACCEPTED = 2;
    const REJECTED = 3;
    const ONWAY = 4;
    const DELIVERING = 5;
    const DONE = 6;
    const SOS = 7;
}
