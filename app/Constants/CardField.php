<?php

namespace App\Constants;

/**
 * Database column names for TCG card-specific fields on the products table.
 * Use CardField::CONDITION->value instead of hardcoding 'card_condition'.
 */
enum CardField: string
{
    case CONDITION = 'card_condition';
    case LANGUAGE  = 'card_language';
    case SET       = 'card_set';
    case RARITY    = 'card_rarity';
    case GRADING   = 'card_grading';
    case GRADE     = 'card_grade';
    case CERT      = 'card_cert';
    case TYPE      = 'card_type';
}
