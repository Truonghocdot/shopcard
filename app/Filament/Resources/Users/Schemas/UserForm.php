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
                Section::make(__('filament.user_stats'))
                    ->columns(3)
                    ->schema([
                        \Filament\Forms\Components\Placeholder::make('total_deposit')
                            ->label(__('filament.user_total_deposit'))
                            ->content(function ($record) {
                                if (!$record) return 0;
                                return \Illuminate\Support\Number::currency(
                                    $record->transactions()->where('status', 1)->sum('amount'),
                                    'VND'
                                );
                            }),
                        \Filament\Forms\Components\Placeholder::make('total_order_spend')
                            ->label(__('filament.user_total_spent'))
                            ->content(function ($record) {
                                if (!$record) return 0;
                                return \Illuminate\Support\Number::currency(
                                    $record->orders()->where('status', 1)->sum('final_amount'),
                                    'VND'
                                );
                            }),
                        \Filament\Forms\Components\Placeholder::make('total_products')
                            ->label(__('filament.user_total_products'))
                            ->content(function ($record) {
                                if (!$record) return 0;
                                return $record->orders()->where('status', 1)->count();
                            }),
                    ]),
                TextInput::make('name')
                    ->label(__('filament.user_name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('filament.user_email'))
                    ->required(),
                TextInput::make('phone')
                    ->label(__('filament.user_phone'))
                    ->tel(),
                Group::make()
                    ->relationship('wallet')
                    ->schema([
                        TextInput::make('balance')
                            ->label(__('filament.user_balance'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),
                TextInput::make('password')
                    ->label(__('filament.user_password'))
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn(string $state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn(?string $state) => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('filament.user_status'))
                    ->required()
                    ->options([
                        1 => __('filament.user_status_active'),
                        0 => __('filament.user_status_locked'),
                    ])
                    ->default(1),
            ]);
    }
}
