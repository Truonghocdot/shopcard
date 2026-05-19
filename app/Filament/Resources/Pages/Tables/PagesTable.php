<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Models\Page;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('filament.field_id'))
                    ->sortable(),

                TextColumn::make('title')
                    ->label(__('filament.page_title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('filament.field_slug'))
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('status')
                    ->label(__('filament.field_status'))
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => $state === Page::STATUS_ACTIVE
                        ? __('filament.field_active')
                        : __('filament.field_inactive'))
                    ->color(fn (int $state): string => $state === Page::STATUS_ACTIVE ? 'success' : 'danger')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label(__('filament.field_updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.field_status'))
                    ->options([
                        Page::STATUS_ACTIVE   => __('filament.field_active'),
                        Page::STATUS_INACTIVE => __('filament.field_inactive'),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
