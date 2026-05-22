<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\DeleteAction;
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
                    ->label(__('filament.banner_sort'))
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('image')
                    ->label(__('filament.banner_image'))
                    ->disk('public')
                    ->height(60)
                    ->extraImgAttributes(['class' => 'rounded object-cover']),

                ImageColumn::make('mobile_image')
                    ->label(__('filament.banner_mobile_image'))
                    ->disk('public')
                    ->height(60)
                    ->extraImgAttributes(['class' => 'rounded object-cover'])
                    ->defaultImageUrl(fn () => null)
                    ->placeholder('—'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('sort', 'asc');
    }
}
