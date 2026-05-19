<?php

namespace App\Filament\Pages;

use App\Constants\SettingName;
use App\Models\Setting as ModelsSetting;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Setting extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Squares2x2;
    protected string $view = 'filament.pages.setting';
    protected static ?string $navigationLabel = null;

    public static function getNavigationLabel(): string
    {
        return __('filament.nav_settings');
    }
    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = ModelsSetting::pluck('setting_value', 'setting_name')->toArray();

        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make(__('filament.setting_general'))
                    ->description(__('filament.setting_general_desc'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        Section::make(__('filament.setting_bank_info'))
                            ->description(__('filament.setting_bank_info_desc'))
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                TextInput::make(SettingName::BIN_BANK->value)
                                    ->label(__('filament.setting_bank_bin'))
                                    ->placeholder(__('filament.setting_bank_bin_ph'))
                                    ->required()
                                    ->helperText(__('filament.setting_bank_bin_help')),

                                TextInput::make(SettingName::ACCOUNT_NUMBER->value)
                                    ->label(__('filament.setting_account_number'))
                                    ->placeholder(__('filament.setting_account_number_ph'))
                                    ->required(),

                                TextInput::make(SettingName::ACCOUNT_NAME->value)
                                    ->label(__('filament.setting_account_name'))
                                    ->placeholder(__('filament.setting_account_name_ph'))
                                    ->required()
                                    ->extraInputAttributes(['style' => 'text-transform: uppercase']),

                                TextInput::make(SettingName::PHONE_NUMBER->value)
                                    ->label(__('filament.setting_phone'))
                                    ->placeholder(__('filament.setting_phone_ph'))
                                    ->required(),

                                TextInput::make(SettingName::ZALO_LINK->value)
                                    ->label(__('filament.setting_zalo'))
                                    ->placeholder(__('filament.setting_zalo_ph'))
                                    ->required(),

                                TextInput::make(SettingName::FACEBOOK_LINK->value)
                                    ->label(__('filament.setting_facebook'))
                                    ->placeholder(__('filament.setting_facebook_ph'))
                                    ->required(),

                                TextInput::make(SettingName::BANKING->value)
                                    ->label(__('filament.setting_bank_name'))
                                    ->placeholder(__('filament.setting_bank_name_ph'))
                                    ->required(),
                                RichEditor::make(SettingName::POPUP_CONTENT->value)
                                    ->label(__('filament.setting_popup'))
                                    ->placeholder(__('filament.setting_popup_ph'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $formData = $this->form->getState();

            foreach ($formData as $key => $value) {
                ModelsSetting::set($key, $value);
            }

            Notification::make()
                ->title(__('filament.setting_save_success'))
                ->body(__('filament.setting_save_success_body'))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('filament.setting_save_error'))
                ->body(__('filament.setting_save_error_body') . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
