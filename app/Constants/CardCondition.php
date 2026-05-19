<?php

namespace App\Constants;

enum CardCondition: string
{
    case NEAR_MINT        = 'NM';
    case LIGHTLY_PLAYED   = 'LP';
    case MODERATELY_PLAYED = 'MP';
    case HEAVILY_PLAYED   = 'HP';
    case DAMAGED          = 'DMG';

    public function label(): string
    {
        return match ($this) {
            self::NEAR_MINT         => 'Near Mint (NM)',
            self::LIGHTLY_PLAYED    => 'Lightly Played (LP)',
            self::MODERATELY_PLAYED => 'Moderately Played (MP)',
            self::HEAVILY_PLAYED    => 'Heavily Played (HP)',
            self::DAMAGED           => 'Damaged (DMG)',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn ($c) => ['value' => $c->value, 'label' => $c->label()], self::cases()),
            'label',
            'value'
        );
    }
}
