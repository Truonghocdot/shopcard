<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")
                    ->label("ID")
                    ->searchable(),
                TextColumn::make('title')
                    ->label(fn() => __('card_title'))
                    ->searchable()
                    ->limit(50),
                TextColumn::make('slug')
                    ->label(fn() => __('card_slug'))
                    ->searchable()
                    ->formatStateUsing(fn($state, $record) => $state . ' - ' . __('discount_label') . ': ' . $record->getDiscountPercent() . '%')
                    ->limit(50),
                TextColumn::make('category.title')
                    ->label(fn() => __('card_category'))
                    ->searchable(),
                TextColumn::make('sell_price')
                    ->label(fn() => __('regular_price'))
                    ->searchable()
                    ->limit(50),
                TextColumn::make('sale_price')
                    ->label(fn() => __('discounted_price'))
                    ->searchable()
                    ->limit(50),
                TextColumn::make('status')
                    ->label(fn() => __('status'))
                    ->formatStateUsing(fn($state) => Product::labelStatus($state)),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
        ;
    }
}
