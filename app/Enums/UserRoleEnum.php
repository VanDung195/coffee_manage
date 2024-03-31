<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserRoleEnum extends Enum
{
    public const ADMIN = 0; //Chủ quán
    public const MANAGER = 1; //Quản lý
    public const CASHIER = 2; //Thu ngân
    public const STAFF = 3; //Nhân viên còn lại (pha chế, phục vụ, giữ xe)
}
