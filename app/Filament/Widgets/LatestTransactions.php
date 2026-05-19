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
                    ->label('Thời gian')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Người dùng')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Số tiền')
                    ->money('VND')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Loại')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state == 0 ? 'Thẻ cào' : 'Ngân hàng')
                    ->color(fn($state) => $state == 0 ? 'info' : 'success'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        0 => 'Chờ xử lý',
                        1 => 'Thành công',
                        2 => 'Thất bại',
                        default => 'Không xác định',
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
