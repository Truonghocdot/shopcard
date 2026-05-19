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
                TextColumn::make("id")
                    ->label("ID")
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Tên')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable(),
                TextColumn::make('role')
                    ->label('Vai trò')
                    ->formatStateUsing(fn($state) => UserRole::getRoleName($state))
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn($state) => $state == 1 ? 'Hoạt động' : 'Khóa')
                    ->badge()
                    ->color(fn($state) => $state ? 'danger' : 'success')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
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
            ->defaultSort('created_at', 'desc') ;
    }
}
