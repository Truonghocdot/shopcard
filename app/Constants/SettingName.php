<?php

namespace App\Constants;

enum SettingName: string
{
    case BIN_BANK = 'bin_bank';
    case ACCOUNT_NUMBER = 'account_number';
    case ACCOUNT_NAME = 'account_name';
    case PHONE_NUMBER = 'phone_number';
    case ZALO_LINK = 'zalo_link';
    case FACEBOOK_LINK = 'facebook_link';
    case BANKING = 'banking';
    case POPUP_CONTENT = 'popup_content';
}