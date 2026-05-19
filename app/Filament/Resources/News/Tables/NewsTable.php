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
                    ->label("Tiêu đề")
                    ->searchable(),
                TextColumn::make("slug")
                    ->label("Đường dẫn")
                    ->searchable(),
                TextColumn::make("description")
                    ->label("Mô tả")
                    ->searchable(),
                TextColumn::make("content")
                    ->label("Nội dung")
                    ->searchable(),
                TextColumn::make("meta_title")
                    ->label("Tiêu đề SEO")
                    ->searchable(),
                TextColumn::make("meta_description")
                    ->label("Mô tả SEO")
                    ->searchable(),
                TextColumn::make("published")
                    ->label("Ngày đăng")
                    ->searchable(),
                TextColumn::make("view_count")
                    ->label("Lượt xem")
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
