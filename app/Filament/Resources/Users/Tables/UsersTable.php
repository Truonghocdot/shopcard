<?php

namespace App\Filament\Resources\Users\Tables;

use App\Constants\UserRole;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.field_id'))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('filament.user_name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('filament.user_email'))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('filament.user_phone'))
                    ->searchable(),
                TextColumn::make('role')
                    ->label(__('filament.user_role'))
                    ->formatStateUsing(fn ($state) => UserRole::getRoleName($state))
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('filament.user_status'))
                    ->formatStateUsing(fn ($state) => $state == 1
                        ? __('filament.user_status_active')
                        : __('filament.user_status_locked'))
                    ->badge()
                    ->color(fn ($state) => $state == 1 ? 'success' : 'danger')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('filament.user_created_at'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
