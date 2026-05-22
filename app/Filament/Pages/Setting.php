<?php

namespace App\Filament\Pages;

use App\Constants\SettingName;
use App\Models\Setting as ModelsSetting;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
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

        $this->form->fill($this->normalizeSettings($settings));
    }

    public function form(Schema $schema): Schema
    {
        $localizedFields = [
            SettingName::SITE_NAME->value,
            SettingName::SITE_TAGLINE->value,
            SettingName::SITE_DESCRIPTION->value,
            SettingName::FOOTER_ABOUT->value,
            SettingName::FOOTER_COPYRIGHT->value,
            SettingName::SUPPORT_HOURS->value,
            SettingName::POPUP_CONTENT->value,
        ];

        return $schema
            ->schema([
                Section::make(__('filament.setting_general'))
                    ->description(__('filament.setting_general_desc'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        Section::make(__('filament.setting_site_content'))
                            ->description(__('filament.setting_site_content_desc'))
                            ->icon('heroicon-o-language')
                            ->schema([
                                $this->localizedTabs(SettingName::SITE_NAME->value, __('filament.setting_site_name')),
                                $this->localizedTabs(SettingName::SITE_TAGLINE->value, __('filament.setting_site_tagline')),
                                $this->localizedTabs(SettingName::SITE_DESCRIPTION->value, __('filament.setting_site_description'), true),
                                $this->localizedTabs(SettingName::FOOTER_ABOUT->value, __('filament.setting_footer_about'), true),
                                $this->localizedTabs(SettingName::FOOTER_COPYRIGHT->value, __('filament.setting_footer_copyright')),
                                $this->localizedTabs(SettingName::SUPPORT_HOURS->value, __('filament.setting_support_hours')),
                                $this->localizedTabs(SettingName::POPUP_CONTENT->value, __('filament.setting_popup'), true),
                            ])
                            ->columns(1),

                        Section::make(__('filament.setting_contact_info'))
                            ->description(__('filament.setting_contact_info_desc'))
                            ->icon('heroicon-o-phone')
                            ->schema([
                                TextInput::make(SettingName::SITE_CONTACT_EMAIL->value)
                                    ->label(__('filament.setting_support_email'))
                                    ->placeholder('support@example.com')
                                    ->email()
                                    ->required(),

                                TextInput::make(SettingName::SITE_CONTACT_PHONE->value)
                                    ->label(__('filament.setting_support_phone'))
                                    ->placeholder(__('filament.setting_phone_ph'))
                                    ->required(),

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

                                TextInput::make(SettingName::INSTAGRAM_LINK->value)
                                    ->label(__('filament.setting_instagram'))
                                    ->placeholder(__('filament.setting_instagram_ph'))
                                    ->url(),

                                TextInput::make(SettingName::TIKTOK_LINK->value)
                                    ->label(__('filament.setting_tiktok'))
                                    ->placeholder(__('filament.setting_tiktok_ph'))
                                    ->url(),
                            ])
                            ->columns(2),

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

                                TextInput::make(SettingName::BANKING->value)
                                    ->label(__('filament.setting_bank_name'))
                                    ->placeholder(__('filament.setting_bank_name_ph'))
                                    ->required(),
                            ])
                            ->columns(2)
                    ]),

                Section::make(__('filament.setting_payment'))
                    ->description(__('filament.setting_payment_desc'))
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        Toggle::make(SettingName::PAYPAL_ENABLED->value)
                            ->label(__('filament.setting_paypal_enabled'))
                            ->default(true)
                            ->inline(false),

                        TextInput::make(SettingName::PAYPAL_CLIENT_ID->value)
                            ->label(__('filament.setting_paypal_client_id'))
                            ->placeholder(__('filament.setting_paypal_client_id_ph'))
                            ->required(),

                        Select::make(SettingName::PAYPAL_ENVIRONMENT->value)
                            ->label(__('filament.setting_paypal_environment'))
                            ->options([
                                'sandbox' => __('filament.setting_paypal_environment_sandbox'),
                                'live' => __('filament.setting_paypal_environment_live'),
                            ])
                            ->default('sandbox')
                            ->required(),

                        TextInput::make(SettingName::PAYPAL_CURRENCY->value)
                            ->label(__('filament.setting_paypal_currency'))
                            ->default('USD')
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $formData = $this->form->getState();

            foreach ($formData as $key => $value) {
                if (is_array($value)) {
                    ModelsSetting::set($key, json_encode($value, JSON_UNESCAPED_UNICODE));
                    continue;
                }

                ModelsSetting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value);
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

    private function normalizeSettings(array $settings): array
    {
        $localizedFields = [
            SettingName::SITE_NAME->value,
            SettingName::SITE_TAGLINE->value,
            SettingName::SITE_DESCRIPTION->value,
            SettingName::FOOTER_ABOUT->value,
            SettingName::FOOTER_COPYRIGHT->value,
            SettingName::SUPPORT_HOURS->value,
            SettingName::POPUP_CONTENT->value,
        ];

        foreach ($localizedFields as $field) {
            $settings[$field] = $this->decodeLocalizedValue($settings[$field] ?? null);
        }

        $settings[SettingName::PAYPAL_ENABLED->value] = filter_var(
            $settings[SettingName::PAYPAL_ENABLED->value] ?? '1',
            FILTER_VALIDATE_BOOL,
            FILTER_NULL_ON_FAILURE
        ) ?? true;

        return $settings;
    }

    private function decodeLocalizedValue(?string $value): array
    {
        $locales = array_keys(config('locales.supported', ['en' => 'English']));
        $defaultLocale = config('locales.default', 'en');

        if (blank($value)) {
            return collect($locales)->mapWithKeys(fn (string $locale) => [$locale => null])->all();
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return collect($locales)->mapWithKeys(fn (string $locale) => [$locale => $decoded[$locale] ?? null])->all();
        }

        return collect($locales)->mapWithKeys(fn (string $locale) => [$locale => $locale === $defaultLocale ? $value : null])->all();
    }

    private function localizedTabs(string $field, string $label, bool $richEditor = false): Tabs
    {
        $locales = config('locales.supported', ['en' => 'English']);

        return Tabs::make($field . '_tabs')
            ->label($label)
            ->tabs(
                collect($locales)->map(function (string $localeLabel, string $locale) use ($field, $label, $richEditor) {
                    $component = $richEditor
                        ? RichEditor::make("{$field}.{$locale}")
                            ->label($label)
                            ->placeholder($label)
                            ->extraInputAttributes(['style' => 'min-height: 20vh;'])
                        : TextInput::make("{$field}.{$locale}")
                            ->label($label)
                            ->placeholder($label)
                            ->maxLength(255);

                    return Tab::make(strtoupper($locale))
                        ->label($localeLabel)
                        ->schema([$component]);
                })->values()->all()
            )
            ->columnSpanFull();
    }
}
