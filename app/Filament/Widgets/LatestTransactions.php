<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTransactions extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->with('user')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.transaction_table_created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament.transaction_table_user'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('filament.transaction_table_amount'))
                    ->money('VND')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.transaction_table_type'))
                    ->badge()
                    ->formatStateUsing(fn($state) => $state == 0 ? __('filament.transaction_type_card') : __('filament.transaction_type_bank'))
                    ->color(fn($state) => $state == 0 ? 'info' : 'success'),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('filament.transaction_table_status'))
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        0 => __('filament.transaction_status_pending'),
                        1 => __('filament.transaction_status_success'),
                        2 => __('filament.transaction_status_failed'),
                        default => __('filament.transaction_status_unknown'),
                    })
                    ->color(fn($state) => match ($state) {
                        0 => 'warning',
                        1 => 'success',
                        2 => 'danger',
                        default => 'gray',
                    }),
            ]);
    }
}
