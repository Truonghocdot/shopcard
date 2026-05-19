<?php

namespace App\Constants;

enum UserRole: int
{
    case CLIENT = 0;
    case ADMIN = 1;

    public static function getRoleName(int $role): string
    {
        return match ($role) {
            self::CLIENT->value => __('filament.user_role_client'),
            self::ADMIN->value  => __('filament.user_role_admin'),
            default             => __('filament.user_role_unknown'),
        };
    }

    public static function getRoleOptions(): array
    {
        return [
            self::CLIENT->value => __('filament.user_role_client'),
            self::ADMIN->value  => __('filament.user_role_admin'),
        ];
    }
}
