<?php

namespace App\Filament\Resources\Coupons\Tables;

use App\Models\Coupon;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Mã giảm giá')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Mô tả')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('discount_type')
                    ->label('Loại giảm giá')
                    ->formatStateUsing(fn($state): string => $state == Coupon::DISCOUNT_PERCENTAGE ? 'Phần trăm' : 'Cố định')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('discount_value')
                    ->label('Giá trị giảm giá')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('max_discount')
                    ->label('Giảm giá tối đa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('min_order_amount')
                    ->label('Đơn hàng tối thiểu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('usage_limit')
                    ->label('Giới hạn sử dụng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('usage_count')
                    ->label('Số lần sử dụng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('usage_per_user')
                    ->label('Số lần sử dụng trên mỗi người dùng')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Ngày kết thúc')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn($state): string => $state == Coupon::STATUS_ACTIVE ? 'Hoạt động' : 'Không hoạt động')
                    ->searchable()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
