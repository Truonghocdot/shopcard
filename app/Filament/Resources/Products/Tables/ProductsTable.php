<?php

namespace App\Filament\Resources\Products\Tables;

use App\Constants\CardCondition;
use App\Constants\CardField;
use App\Constants\CardGrading;
use App\Constants\CardLanguage;
use App\Constants\CardType;
use App\Models\Product;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.field_id'))
                    ->sortable(),

                TextColumn::make('title')
                    ->label(__('card_title'))
                    ->searchable()
                    ->limit(40),

                TextColumn::make('category.title')
                    ->label(__('card_category'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make(CardField::TYPE->value)
                    ->label(__('filament.card_type'))
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => CardType::from($state)->label())
                    ->color(fn (int $state): string => match ($state) {
                        CardType::SINGLE->value => 'info',
                        CardType::SEALED->value => 'warning',
                        CardType::BUNDLE->value => 'success',
                        default                 => 'gray',
                    }),

                TextColumn::make(CardField::CONDITION->value)
                    ->label(__('card_condition'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        CardCondition::NEAR_MINT->value        => 'success',
                        CardCondition::LIGHTLY_PLAYED->value   => 'info',
                        CardCondition::MODERATELY_PLAYED->value => 'warning',
                        CardCondition::HEAVILY_PLAYED->value   => 'danger',
                        CardCondition::DAMAGED->value          => 'danger',
                        default                                => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make(CardField::GRADING->value)
                    ->label(__('filament.card_grading'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        CardGrading::PSA->value => 'danger',
                        CardGrading::BGS->value => 'warning',
                        CardGrading::CGC->value => 'info',
                        default                 => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make(CardField::GRADE->value)
                    ->label(__('filament.card_grade'))
                    ->toggleable(),

                TextColumn::make(CardField::LANGUAGE->value)
                    ->label(__('card_language'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make(CardField::SET->value)
                    ->label(__('card_set_expansion'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make(CardField::RARITY->value)
                    ->label(__('card_rarity'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('sell_price')
                    ->label(__('regular_price'))
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('sale_price')
                    ->label(__('discounted_price'))
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('status'))
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => Product::labelStatus($state))
                    ->color(fn (int $state): string => $state === Product::STATUS_UNSOLD ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make(CardField::TYPE->value)
                    ->label(__('filament.card_type'))
                    ->options(CardType::options()),

                SelectFilter::make(CardField::CONDITION->value)
                    ->label(__('card_condition'))
                    ->options(CardCondition::options()),

                SelectFilter::make(CardField::GRADING->value)
                    ->label(__('filament.card_grading'))
                    ->options(CardGrading::options()),

                SelectFilter::make(CardField::LANGUAGE->value)
                    ->label(__('card_language'))
                    ->options(CardLanguage::options()),

                SelectFilter::make('status')
                    ->label(__('status'))
                    ->options([
                        Product::STATUS_UNSOLD => 'Unsold',
                        Product::STATUS_SOLD   => 'Sold',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
