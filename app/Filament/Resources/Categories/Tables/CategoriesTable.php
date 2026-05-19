<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Đường dẫn')
                    ->searchable(),
                ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public')
                    ->searchable(),
                TextColumn::make('parent.title')
                    ->label('Danh mục cha')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
