<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort')
                    ->label('Thứ tự')
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('sort', 'asc');
    }
}
