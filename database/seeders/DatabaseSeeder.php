<?php

namespace Database\Seeders;

use App\Constants\SettingName;
use App\Constants\UserRole;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createAdminUser();
        $this->initSetting();
    }

    private function createAdminUser(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@vanhfco.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
        ]);
    }

    private function initSetting()
    {
        Setting::create([
            'setting_name' => SettingName::BIN_BANK->value,
            'setting_value' => '970422', // MB Bank BIN
        ]);

        Setting::create([
            'setting_name' => SettingName::ACCOUNT_NUMBER->value,
            'setting_value' => '0986526036',
        ]);

        Setting::create([
            'setting_name' => SettingName::ACCOUNT_NAME->value,
            'setting_value' => 'LE VIET ANH',
        ]);

        Setting::create([
            'setting_name' => SettingName::PHONE_NUMBER->value,
            'setting_value' => '0986526036',
        ]);

        Setting::create([
            'setting_name' => SettingName::ZALO_LINK->value,
            'setting_value' => 'https://zalo.me/0986526036',
        ]);

        Setting::create([
            'setting_name' => SettingName::FACEBOOK_LINK->value,
            'setting_value' => 'https://www.facebook.com/le.vietanh.939173',
        ]);

        Setting::create([
            'setting_name' => SettingName::BANKING->value,
            'setting_value' => 'VP Bank',
        ]);
    }
}
