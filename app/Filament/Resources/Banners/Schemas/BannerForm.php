<?php

namespace App\Filament\Resources\Banners\Schemas;

use App\Filament\Traits\HandlesWebpUploads;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sort')
                    ->label(__('filament.banner_sort'))
                    ->required()
                    ->numeric()
                    ->validationMessages([
                        'required' => __('filament.banner_sort_required'),
                        'numeric'  => __('filament.banner_sort_numeric'),
                    ]),

                Section::make(__('filament.banner_images_section'))
                    ->description(__('filament.banner_images_section_desc'))
                    ->columns(2)
                    ->schema([
                        HandlesWebpUploads::processImageUpload(
                            FileUpload::make('image')
                                ->required()
                                ->label(__('filament.banner_image'))
                                ->helperText(__('filament.banner_image_hint'))
                                ->disk('public')
                                ->directory('banners')
                                ->image()
                                ->imagePreviewHeight('160')
                                ->validationMessages([
                                    'required' => __('filament.banner_image_required'),
                                ])
                        ),

                        HandlesWebpUploads::processImageUpload(
                            FileUpload::make('mobile_image')
                                ->label(__('filament.banner_mobile_image'))
                                ->helperText(__('filament.banner_mobile_image_hint'))
                                ->disk('public')
                                ->directory('banners/mobile')
                                ->image()
                                ->imagePreviewHeight('160')
                                ->nullable()
                        ),
                    ]),
            ]);
    }
}
