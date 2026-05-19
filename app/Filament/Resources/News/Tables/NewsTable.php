<?php

namespace App\Filament\Resources\News\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("title")
                    ->label(__('filament.news_title'))
                    ->searchable(),
                TextColumn::make("slug")
                    ->label(__('filament.news_slug'))
                    ->searchable(),
                TextColumn::make("description")
                    ->label(__('filament.news_description'))
                    ->searchable(),
                TextColumn::make("content")
                    ->label(__('filament.news_content'))
                    ->searchable(),
                TextColumn::make("meta_title")
                    ->label(__('filament.news_meta_title'))
                    ->searchable(),
                TextColumn::make("meta_description")
                    ->label(__('filament.news_meta_description'))
                    ->searchable(),
                TextColumn::make("published")
                    ->label(__('filament.news_published'))
                    ->searchable(),
                TextColumn::make("view_count")
                    ->label(__('filament.news_view_count'))
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
