<?php

namespace App\Constants;

enum CardGrading: string
{
    case PSA = 'PSA';
    case BGS = 'BGS';
    case CGC = 'CGC';
    case RAW = 'RAW';

    public function label(): string
    {
        return match ($this) {
            self::PSA => 'PSA (Professional Sports Authenticator)',
            self::BGS => 'BGS (Beckett Grading Services)',
            self::CGC => 'CGC (Certified Guaranty Company)',
            self::RAW => 'Raw (Ungraded)',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn ($g) => ['value' => $g->value, 'label' => $g->label()], self::cases()),
            'label',
            'value'
        );
    }

    /** Valid numeric grades per grading company */
    public function grades(): array
    {
        return match ($this) {
            self::PSA => ['10', '9', '8', '7', '6', '5', '4', '3', '2', '1'],
            self::BGS => ['10', '9.5', '9', '8.5', '8', '7.5', '7', '6.5', '6', '5.5', '5'],
            self::CGC => ['10', '9.5', '9', '8.5', '8', '7.5', '7', '6.5', '6', '5.5', '5'],
            self::RAW => [],
        };
    }
}
