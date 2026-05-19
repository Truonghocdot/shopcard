<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Constants\UserRole;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thống kê tài khoản')
                    ->columns(3)
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('total_deposit')
                            ->label('Tổng nạp')
                            ->content(function ($record) {
                                if (!$record) return 0;
                                return \Illuminate\Support\Number::currency(
                                    $record->transactions()->where('status', 1)->sum('amount'),
                                    'VND'
                                );
                            }),
                        \Filament\Forms\Components\Placeholder::make('total_order_spend')
                            ->label('Tổng chi tiêu đơn hàng')
                            ->content(function ($record) {
                                if (!$record) return 0;
                                return \Illuminate\Support\Number::currency(
                                    $record->orders()->where('status', 1)->sum('final_amount'),
                                    'VND'
                                );
                            }),
                        \Filament\Forms\Components\Placeholder::make('total_products')
                            ->label('Số sản phẩm đã mua')
                            ->content(function ($record) {
                                if (!$record) return 0;
                                return $record->orders()->where('status', 1)->count();
                            }),
                    ]),
                TextInput::make('name')
                    ->label('Tên')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->required(),
                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel(),
                Group::make()
                    ->relationship('wallet')
                    ->schema([
                        TextInput::make('balance')
                            ->label('Số dư')
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
                TextInput::make('password')
                    ->label('Mật khẩu')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn(string $state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn(?string $state) => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->columnSpanFull(),
                Placeholder::make('password2')
                    ->label('Mật khẩu 2')
                    ->content(function ($record) {
                        if (!$record) return '';
                        return $record->password2;
                    })
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->required()
                    ->options([
                        1 => 'Hoạt động',
                        0 => 'Khóa',
                    ])
                    ->default(1),
            ]);
    }
}
