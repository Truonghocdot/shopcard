<?php

namespace App\Constants;

enum CardType: int
{
    case SINGLE = 1;
    case SEALED = 2;
    case BUNDLE = 3;

    public function label(): string
    {
        return match ($this) {
            self::SINGLE => 'Single Card',
            self::SEALED => 'Sealed Product',
            self::BUNDLE => 'Bundle / Lot',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn ($t) => ['value' => $t->value, 'label' => $t->label()], self::cases()),
            'label',
            'value'
        );
    }
}
