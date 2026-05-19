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
    protected static ?string $navigationLabel = 'Cấu hình';
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
                Section::make('Cấu hình chung')
                    ->description('Quản lý các thông số vận hành hệ thống')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        Section::make('Thông tin tài khoản nhận tiền')
                            ->description('Cấu hình thông tin ngân hàng hiển thị cho khách hàng chuyển khoản')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                TextInput::make(SettingName::BIN_BANK->value)
                                    ->label('Mã BIN Ngân hàng')
                                    ->placeholder('Ví dụ: 970403')
                                    ->required()
                                    ->helperText('Mã BIN của ngân hàng (ví dụ: 970403 cho Sacombank)'),

                                TextInput::make(SettingName::ACCOUNT_NUMBER->value)
                                    ->label('Số tài khoản')
                                    ->placeholder('Nhập số tài khoản ngân hàng')
                                    ->required(),

                                TextInput::make(SettingName::ACCOUNT_NAME->value)
                                    ->label('Tên chủ tài khoản')
                                    ->placeholder('NHAP TEN KHONG DAU')
                                    ->required()
                                    ->extraInputAttributes(['style' => 'text-transform: uppercase']),

                                TextInput::make(SettingName::PHONE_NUMBER->value)
                                    ->label('Số điện thoại')
                                    ->placeholder('Nhập số điện thoại')
                                    ->required(),

                                TextInput::make(SettingName::ZALO_LINK->value)
                                    ->label('Link Zalo')
                                    ->placeholder('Nhập link Zalo')
                                    ->required(),

                                TextInput::make(SettingName::FACEBOOK_LINK->value)
                                    ->label('Link Facebook')
                                    ->placeholder('Nhập link Facebook')
                                    ->required(),

                                TextInput::make(SettingName::BANKING->value)
                                    ->label('Ngân hàng')
                                    ->placeholder('Nhập tên ngân hàng')
                                    ->required(),
                                RichEditor::make(SettingName::POPUP_CONTENT->value)
                                    ->label('Nội dung popup')
                                    ->placeholder('Nhập nội dung popup')
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
                ->title('Lưu thành công')
                ->body('Các cài đặt đã được cập nhật.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Lỗi')
                ->body('Có lỗi xảy ra khi lưu cài đặt: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
