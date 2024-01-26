<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TableIsPaidEnum extends Enum
{
    public const CHUA_THANH_TOAN = 0;
    public const DA_THANH_TOAN = 1;
}
