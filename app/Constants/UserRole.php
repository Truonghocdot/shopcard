<?php

namespace App\Constants;

enum UserRole: int
{
    case CLIENT = 0;
    case ADMIN = 1;

    public static function getRoleName(int $role): string
    {
        return match ($role) {
            self::CLIENT->value => 'Khách hàng',
            self::ADMIN->value => 'Quản trị viên',
            default => 'Không xác định',
        };
    }

    public static function getRoleOptions(): array
    {
        return [
            self::CLIENT->value => 'Khách hàng',
            self::ADMIN->value => 'Quản trị viên',
        ];
    }
}
