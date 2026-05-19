<?php

namespace App\Constants;

enum CardLanguage: string
{
    case ENGLISH    = 'English';
    case JAPANESE   = 'Japanese';
    case KOREAN     = 'Korean';
    case CHINESE_S  = 'Chinese (Simplified)';
    case CHINESE_T  = 'Chinese (Traditional)';
    case GERMAN     = 'German';
    case FRENCH     = 'French';
    case ITALIAN    = 'Italian';
    case SPANISH    = 'Spanish';
    case PORTUGUESE = 'Portuguese';

    public static function options(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_column(self::cases(), 'value')
        );
    }
}
