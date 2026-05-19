<?php

namespace App\Filament\Resources\Coupons\Schemas;

use App\Models\Coupon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Mã giảm giá')
                    ->required()
                    ->validationMessages([
                        'required' => 'Mã giảm giá không được để trống',
                    ]),
                TextInput::make('description')
                    ->label('Mô tả')
                    ->required()
                    ->validationMessages([
                        'required' => 'Mô tả không được để trống',
                    ]),
                Select::make('discount_type')
                    ->label('Loại giảm giá')
                    ->options([
                        Coupon::DISCOUNT_PERCENTAGE => 'Phần trăm',
                        Coupon::DISCOUNT_FIXED => 'Cố định',
                    ])
                    ->required()
                    ->validationMessages([
                        'required' => 'Loại giảm giá không được để trống',
                    ]),
                TextInput::make('discount_value')
                    ->label('Giá trị giảm giá')
                    ->required()
                    ->validationMessages([
                        'required' => 'Giá trị giảm giá không được để trống',
                    ]),
                TextInput::make('max_discount')
                    ->label('Giảm giá tối đa')
                    ->required()
                    ->validationMessages([
                        'required' => 'Giảm giá tối đa không được để trống',
                    ]),
                TextInput::make('min_order_amount')
                    ->label('Đơn hàng tối thiểu')
                    ->required()
                    ->validationMessages([
                        'required' => 'Đơn hàng tối thiểu không được để trống',
                    ]),
                TextInput::make('usage_limit')
                    ->label('Giới hạn sử dụng')
                    ->numeric()
                    ->required()
                    ->validationMessages([
                        'required' => 'Giới hạn sử dụng không được để trống',
                    ]),
                TextInput::make('usage_count')
                    ->label('Số lần sử dụng')
                    ->disabled(fn($livewire) => $livewire instanceof CreateRecord)
                    ->numeric(),
                TextInput::make('usage_per_user')
                    ->label('Số lần sử dụng trên mỗi người dùng')
                    ->numeric()
                    ->required()
                    ->validationMessages([
                        'required' => 'Số lần sử dụng trên mỗi người dùng không được để trống',
                    ]),
                DatePicker::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->required()
                    ->validationMessages([
                        'required' => 'Ngày bắt đầu không được để trống',
                    ]),
                DatePicker::make('end_date')
                    ->label('Ngày kết thúc')
                    ->required()
                    ->validationMessages([
                        'required' => 'Ngày kết thúc không được để trống',
                    ]),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        Coupon::STATUS_INACTIVE => 'Không hoạt động',
                        Coupon::STATUS_ACTIVE => 'Hoạt động',
                    ])
                    ->required()
                    ->validationMessages([
                        'required' => 'Trạng thái không được để trống',
                    ]),
                Section::make('Loại trừ theo danh mục và khoảng giá')
                    ->description('Mã giảm giá sẽ không áp dụng cho sản phẩm thuộc danh mục đã chọn VÀ có giá nằm trong khoảng giá chỉ định.')
                    ->columns(3)
                    ->schema([
                        Select::make('excludedCategories')
                            ->label('Danh mục loại trừ')
                            ->relationship('excludedCategories', 'title')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        TextInput::make('excluded_min_price')
                            ->label('Giá tối thiểu loại trừ')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('đ'),
                        TextInput::make('excluded_max_price')
                            ->label('Giá tối đa loại trừ')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('đ'),
                    ]),
            ]);
    }
}
