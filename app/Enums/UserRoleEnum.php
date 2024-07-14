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
    public const ADMIN = 1; //Chủ quán
    public const MANAGER = 2; //Quản lý
    public const CASHIER = 3; //Thu ngân
    public const BARTENDER = 4; //Pha chế
    public const STAFF = 5; //Phục vụ 

    public static function getRoleForRegister(): array
    {
        return [
            'cashier' => self::CASHIER,
            'bartender' => self::BARTENDER,
            'staff' => self::STAFF,
        ];
    }
    public static function getRole(): array
    {
        return [
            'Manager' => self::MANAGER,
            'Cashier' => self::CASHIER,
            'Bartender' => self::BARTENDER,
            'Staff' => self::STAFF,
        ];
    }
}
