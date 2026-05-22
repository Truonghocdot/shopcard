<?php

namespace App\Constants;

enum SettingName: string
{
    case SITE_NAME = 'site_name';
    case SITE_TAGLINE = 'site_tagline';
    case SITE_DESCRIPTION = 'site_description';
    case SITE_CONTACT_EMAIL = 'site_contact_email';
    case SITE_CONTACT_PHONE = 'site_contact_phone';
    case FOOTER_ABOUT = 'footer_about';
    case FOOTER_COPYRIGHT = 'footer_copyright';
    case SUPPORT_HOURS = 'support_hours';
    case BIN_BANK = 'bin_bank';
    case ACCOUNT_NUMBER = 'account_number';
    case ACCOUNT_NAME = 'account_name';
    case PHONE_NUMBER = 'phone_number';
    case ZALO_LINK = 'zalo_link';
    case FACEBOOK_LINK = 'facebook_link';
    case TIKTOK_LINK = 'tiktok_link';
    case INSTAGRAM_LINK = 'instagram_link';
    case BANKING = 'banking';
    case POPUP_CONTENT = 'popup_content';
    case PAYPAL_ENABLED = 'paypal_enabled';
    case PAYPAL_CLIENT_ID = 'paypal_client_id';
    case PAYPAL_CURRENCY = 'paypal_currency';
    case PAYPAL_ENVIRONMENT = 'paypal_environment';
}
