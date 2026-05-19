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
                    ->label("Người mua"),
                TextColumn::make("amount")
                    ->label("Tổng tiền"),
                TextColumn::make("status")
                    ->label("Trạng thái")
                    ->formatStateUsing(fn($state) => Transaction::labelStatus($state)),
                TextColumn::make("created_at")
                    ->label("Ngày tạo"),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
