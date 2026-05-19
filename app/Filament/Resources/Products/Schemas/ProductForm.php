<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Traits\HandlesWebpUploads;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label(fn() => __('card_category'))
                    ->relationship('category', 'title')
                    ->required()
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                HandlesWebpUploads::processImageUpload(
                    FileUpload::make('images')
                        ->label(fn() => __('card_images'))
                        ->multiple()
                        ->image()
                        ->disk('public')
                        ->required()
                        ->panelLayout('grid')
                        ->validationMessages([
                            'required' => fn() => __('field_required'),
                        ])
                ),
                TextInput::make('title')
                    ->label(fn() => __('card_title'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                        'max_length' => fn() => __('field_max_length'),
                    ])
                    ->live()
                    ->debounce(2500)
                    ->afterStateUpdated(function ($state, callable $set) {
                        $slug = str($state)->slug();
                        $slugOriginal = $slug;
                        $flag = 0;
                        while (Product::where('slug', $slug)->exists()) {
                            $slug = str($slugOriginal)->append('-' . $flag);
                            $flag++;
                        }
                        $set('slug', $slug);
                        $set('meta_title', $state);
                        $set('meta_description', $state);
                    }),
                TextInput::make('slug')
                    ->label(fn() => __('card_slug'))
                    ->required()
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                TextInput::make('username')
                    ->label(fn() => __('psa_cert_serial'))
                    ->required()
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                TextInput::make('phone')
                    ->label(fn() => __('card_condition')),
                TextInput::make('password')
                    ->label(fn() => __('card_language'))
                    ->required()
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                TextInput::make('email')
                    ->label(fn() => __('card_set_expansion'))
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                TextInput::make('password2')
                    ->label(fn() => __('card_rarity'))
                    ->required()
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                RichEditor::make('content')
                    ->label(fn() => __('card_details'))
                    ->required()
                    ->extraInputAttributes(['style' => 'min-height: 20vh;'])
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                TextInput::make('sell_price')
                    ->label(fn() => __('regular_price'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                        'max_length' => fn() => __('field_max_length'),
                    ]),
                TextInput::make('sale_price')
                    ->label(fn() => __('discounted_price'))
                    ->required()
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                TextInput::make('meta_title')
                    ->label(fn() => __('meta_title_label'))
                    ->required()
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
                TextInput::make('meta_description')
                    ->label(fn() => __('meta_desc_label'))
                    ->required()
                    ->validationMessages([
                        'required' => fn() => __('field_required'),
                    ]),
            ]);
    }
}
