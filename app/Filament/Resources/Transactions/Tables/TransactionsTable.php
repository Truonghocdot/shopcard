<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Models\Transaction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("user.name")
                    ->label(__('filament.transaction_table_buyer')),
                TextColumn::make("amount")
                    ->label(__('filament.transaction_table_amount')),
                TextColumn::make("status")
                    ->label(__('filament.transaction_table_status'))
                    ->formatStateUsing(fn($state) => Transaction::labelStatus($state)),
                TextColumn::make("created_at")
                    ->label(__('filament.transaction_table_created_at')),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
