<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Number;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema->components([

            Section::make(__('filament.order_details'))
                ->columns(3)
                ->schema([
                    Placeholder::make('order_number')
                        ->label(__('filament.order_number'))
                        ->content(fn ($record) => $record->order_number),

                    Placeholder::make('status')
                        ->label(__('filament.order_status'))
                        ->content(fn ($record) => match ($record->status) {
                            Order::STATUS_PENDING   => __('filament.order_status_pending'),
                            Order::STATUS_COMPLETED => __('filament.order_status_completed'),
                            Order::STATUS_CANCELLED => __('filament.order_status_cancelled'),
                            Order::STATUS_REFUNDED  => __('filament.order_status_refunded'),
                            default                 => __('filament.order_status_unknown'),
                        }),

                    Placeholder::make('created_at')
                        ->label(__('filament.field_created_at'))
                        ->content(fn ($record) => $record->created_at?->format('d/m/Y H:i')),
                ]),

            Section::make(__('filament.order_customer_info'))
                ->columns(2)
                ->schema([
                    Placeholder::make('user_name')
                        ->label(__('filament.user_name'))
                        ->content(fn ($record) => $record->user?->name ?? '—'),

                    Placeholder::make('user_email')
                        ->label(__('filament.user_email'))
                        ->content(fn ($record) => $record->user?->email ?? '—'),
                ]),

            Section::make(__('filament.order_product_info'))
                ->columns(2)
                ->schema([
                    Placeholder::make('product_title')
                        ->label(__('filament.order_product'))
                        ->content(fn ($record) => $record->product?->title ?? '—'),

                    Placeholder::make('product_category')
                        ->label(__('filament.cat_title'))
                        ->content(fn ($record) => $record->product?->category?->title ?? '—'),

                    Placeholder::make('product_price')
                        ->label(__('filament.order_original_price'))
                        ->content(fn ($record) => Number::currency((float) $record->product_price, 'VND')),

                    Placeholder::make('discount_amount')
                        ->label(__('filament.order_discount'))
                        ->content(fn ($record) => Number::currency((float) $record->discount_amount, 'VND')),

                    Placeholder::make('final_amount')
                        ->label(__('filament.order_total'))
                        ->content(fn ($record) => Number::currency((float) $record->final_amount, 'VND')),

                    Placeholder::make('coupon_code')
                        ->label(__('filament.order_coupon'))
                        ->content(fn ($record) => $record->coupon?->code ?? '—'),
                ]),

            Section::make(__('filament.order_payment_info'))
                ->schema([
                    Placeholder::make('payment_method')
                        ->label(__('filament.order_payment_method'))
                        ->content(function ($record) {
                            if (!$record->notes) return '—';
                            $data = json_decode($record->notes, true);
                            return isset($data['payment_method'])
                                ? strtoupper($data['payment_method'])
                                : '—';
                        }),

                    Placeholder::make('paypal_tx')
                        ->label(__('filament.order_paypal_tx'))
                        ->content(function ($record) {
                            if (!$record->notes) return '—';
                            $data = json_decode($record->notes, true);
                            return $data['paypal_transaction_id'] ?? '—';
                        }),

                    Placeholder::make('shipping_info')
                        ->label(__('filament.order_shipping_info'))
                        ->columnSpanFull()
                        ->content(function ($record) {
                            if (!$record->notes) return '—';
                            $data = json_decode($record->notes, true);
                            if (empty($data['shipping_info'])) return '—';
                            $s = $data['shipping_info'];
                            return implode(' | ', array_filter([
                                $s['name']        ?? null,
                                $s['phone']       ?? null,
                                $s['email']       ?? null,
                                $s['address']     ?? null,
                                $s['city']        ?? null,
                                $s['postal_code'] ?? null,
                                $s['country']     ?? null,
                            ]));
                        }),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('mark_completed')
                ->label(__('filament.order_mark_completed'))
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status === Order::STATUS_PENDING)
                ->requiresConfirmation()
                ->action(fn () => $this->record->markAsCompleted()),

            Action::make('mark_cancelled')
                ->label(__('filament.order_mark_cancelled'))
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => in_array($this->record->status, [Order::STATUS_PENDING, Order::STATUS_COMPLETED]))
                ->requiresConfirmation()
                ->action(fn () => $this->record->markAsCancelled()),
        ];
    }
}
