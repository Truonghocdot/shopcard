<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label(__('filament.order_number'))
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('user.name')
                    ->label(__('filament.order_customer'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.title')
                    ->label(__('filament.order_product'))
                    ->searchable()
                    ->limit(40),

                TextColumn::make('final_amount')
                    ->label(__('filament.order_total'))
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('discount_amount')
                    ->label(__('filament.order_discount'))
                    ->money('VND')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label(__('filament.order_status'))
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Order::STATUS_PENDING   => __('filament.order_status_pending'),
                        Order::STATUS_COMPLETED => __('filament.order_status_completed'),
                        Order::STATUS_CANCELLED => __('filament.order_status_cancelled'),
                        Order::STATUS_REFUNDED  => __('filament.order_status_refunded'),
                        default                 => __('filament.order_status_unknown'),
                    })
                    ->color(fn (int $state): string => match ($state) {
                        Order::STATUS_PENDING   => 'warning',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_CANCELLED => 'danger',
                        Order::STATUS_REFUNDED  => 'info',
                        default                 => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament.field_created_at'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label(__('filament.order_completed_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('filament.order_status'))
                    ->options([
                        Order::STATUS_PENDING   => __('filament.order_status_pending'),
                        Order::STATUS_COMPLETED => __('filament.order_status_completed'),
                        Order::STATUS_CANCELLED => __('filament.order_status_cancelled'),
                        Order::STATUS_REFUNDED  => __('filament.order_status_refunded'),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
